<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductRequest;
use App\Models\Service;
use App\Models\WhyChoose;
use App\Models\Stat;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle the admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'details' => ['message' => 'Logged into the system.'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $productsCount = Product::count();
        $messagesCount = ContactMessage::count();
        $usersCount = User::count();
        $requestsCount = ProductRequest::count();
        $pendingRequestsCount = ProductRequest::where('status', 'pending')->count();
        
        $recentMessages = ContactMessage::latest()->take(5)->get();
        $recentProducts = Product::latest()->take(5)->get();
        $recentRequests = ProductRequest::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'productsCount', 
            'messagesCount', 
            'usersCount', 
            'requestsCount',
            'pendingRequestsCount',
            'recentMessages', 
            'recentProducts',
            'recentRequests'
        ));
    }

    /**
     * Display the products catalog listing.
     */
    public function indexProducts()
    {
        $products = Product::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Display customer contact messages.
     */
    public function indexInquiries()
    {
        $messages = ContactMessage::latest()->get();
        return view('admin.inquiries.index', compact('messages'));
    }

    /**
     * Display customer product requests and orders.
     */
    public function indexRequests(Request $request)
    {
        $query = ProductRequest::latest();

        // Optional filtering by type or status
        if ($request->filled('type')) {
            $query->where('request_type', $request->input('type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->get();

        return view('admin.requests.index', compact('requests'));
    }

    /**
     * Update the status of a product request.
     */
    public function updateRequestStatus(Request $request, ProductRequest $productRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_progress,completed,cancelled,paid,unpaid',
        ]);

        $productRequest->update($validated);

        return back()->with('success', 'Request status updated successfully!');
    }

    /**
     * Delete a product request.
     */
    public function deleteRequest(ProductRequest $productRequest)
    {
        $productRequest->delete();

        return back()->with('success', 'Product request deleted successfully!');
    }

    /**
     * Log the admin out.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'details' => ['message' => 'Logged out of the system.'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    /**
     * Show the form to create a product.
     */
    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in the database.
     */
    public function storeProduct(Request $request)
    {
        $request->merge([
            'is_from_price' => $request->has('is_from_price'),
            'in_stock' => $request->has('in_stock'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_from_price' => 'boolean',
            'in_stock' => 'boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->filled('category_id')) {
            $category = Category::find($request->input('category_id'));
            $validated['badge'] = $category ? $category->name : null;
        } else {
            $validated['badge'] = null;
        }

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure uploads directory exists
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['image_url'] = '/uploads/products/' . $filename;
        } elseif ($request->filled('image_url')) {
            $validated['image_url'] = $request->input('image_url');
        } else {
            return back()->withErrors(['image_file' => 'You must upload an image file or provide a valid image URL.'])->withInput();
        }

        // Remove temp validation field
        unset($validated['image_file']);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    }

    /**
     * Show the form to edit a product.
     */
    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in the database.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->merge([
            'is_from_price' => $request->has('is_from_price'),
            'in_stock' => $request->has('in_stock'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_from_price' => 'boolean',
            'in_stock' => 'boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->filled('category_id')) {
            $category = Category::find($request->input('category_id'));
            $validated['badge'] = $category ? $category->name : null;
        } else {
            $validated['badge'] = null;
        }

        if ($request->hasFile('image_file')) {
            // Delete old file if it was a local upload
            if (str_starts_with($product->image_url, '/uploads/') && file_exists(public_path($product->image_url))) {
                @unlink(public_path($product->image_url));
            }

            $file = $request->file('image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $filename);
            $validated['image_url'] = '/uploads/products/' . $filename;
        } elseif ($request->filled('image_url')) {
            // Delete old file if switching to external URL
            if (str_starts_with($product->image_url, '/uploads/') && file_exists(public_path($product->image_url))) {
                @unlink(public_path($product->image_url));
            }
            $validated['image_url'] = $request->input('image_url');
        } else {
            $validated['image_url'] = $product->image_url;
        }

        unset($validated['image_file']);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Product deleted successfully!');
    }

    /**
     * Reorder products from selection checkboxes.
     */
    public function reorderProducts(Request $request)
    {
        $orders = $request->input('orders', []);

        if (empty($orders)) {
            return back()->with('error', 'No products were selected for reordering.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($orders) {
            foreach ($orders as $id => $order) {
                Product::where('id', $id)->update(['sort_order' => intval($order)]);
            }

            // Get selected IDs
            $selectedIds = array_keys($orders);

            // Get unselected products ordered by current sort_order and id
            $unselectedProducts = Product::whereNotIn('id', $selectedIds)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->select('id')
                ->get();

            // Sequentially update unselected products starting from count($orders) + 1
            $nextOrder = count($orders) + 1;
            foreach ($unselectedProducts as $product) {
                Product::where('id', $product->id)->update(['sort_order' => $nextOrder]);
                $nextOrder++;
            }
        });

        // Log the activity
        try {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'details' => ['message' => 'Reordered products using checkbox selection.', 'count' => count($orders)],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Products reordered successfully!');
    }

    /**
     * Show the form to edit the authenticated user's profile.
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully!');
    }

    /**
     * Display a listing of website managers/users.
     */
    public function indexUsers()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can access user management.');
        }

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form to create a new manager/user.
     */
    public function createUser()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can access user management.');
        }

        return view('admin.users.create');
    }

    /**
     * Store a newly created manager/user in the database.
     */
    public function storeUser(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can access user management.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User registered successfully!');
    }

    /**
     * Show the form to edit a manager/user.
     */
    public function editUser(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can access user management.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.profile.edit')->with('info', 'Please edit your profile here.');
        }
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified manager/user in the database.
     */
    public function updateUser(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can access user management.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.profile.edit')->with('error', 'Use profile section to update your own account.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Delete the specified manager/user from the database.
     */
    public function deleteUser(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can access user management.');
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot delete your own account!']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Display a listing of products categories.
     */
    public function indexCategories()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form to create a new category.
     */
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in database.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Show the form to edit a category.
     */
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in database.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->name = $validated['name'];
        $category->slug = \Illuminate\Support\Str::slug($validated['name']);
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Delete the specified category from database.
     */
    public function deleteCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    /**
     * Display website visitor logs and analytics.
     */
    public function indexVisitorLogs()
    {
        $totalViews = \App\Models\VisitorLog::count();
        $uniqueVisitors = \App\Models\VisitorLog::distinct('ip_address')->count('ip_address');
        
        $topLocations = \App\Models\VisitorLog::select('country', 'city', \DB::raw('count(*) as count'))
            ->groupBy('country', 'city')
            ->orderByDesc('count')
            ->take(5)
            ->get();
            
        $deviceStats = \App\Models\VisitorLog::select('device_type', \DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        $browserStats = \App\Models\VisitorLog::select('browser', \DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $logs = \App\Models\VisitorLog::with('user')->latest()->take(1000)->get();

        return view('admin.logs.visitors', compact(
            'totalViews',
            'uniqueVisitors',
            'topLocations',
            'deviceStats',
            'browserStats',
            'logs'
        ));
    }

    /**
     * Display administrative activity logs.
     */
    public function indexActivityLogs()
    {
        $logs = \App\Models\ActivityLog::with('user')->latest()->take(1000)->get();
        return view('admin.logs.activity', compact('logs'));
    }

    /**
     * Display company branding configurations.
     */
    public function editSettings()
    {
        $officeName = setting('office_name', 'AMSTROOM COMPUTERS');
        $slogan = setting('slogan', 'Technology Innovations • Fast & Reliable');
        $logoPath = setting('logo_path', 'images/logo.png');
        $contactAddress = setting('contact_address', "Shop 101, 2H Plaza\nMorogoro, Tanzania");
        $contactPhone = setting('contact_phone', '+255 710 635 173');
        $contactWhatsapp = setting('contact_whatsapp', '+255 710 635 173');
        $contactEmail = setting('contact_email', 'info@amstroomcomputers.com');
        $contactHours = setting('contact_hours', "Monday - Saturday\n8:00 AM – 7:00 PM");
        $googleMapIframe = setting('google_map_iframe', 'https://maps.google.com/maps?q=Shop%20101,%202H%20Plaza,%20Morogoro,%20Tanzania&t=&z=15&ie=UTF8&iwloc=&output=embed');
        $socialInstagram = setting('social_instagram', 'https://instagram.com/amstroom_computers');
        $socialInstagramHandle = setting('social_instagram_handle', '@amstroom_computers');
        $socialFacebook = setting('social_facebook', 'https://facebook.com/AmstroomComputers');
        $socialFacebookHandle = setting('social_facebook_handle', 'Amstroom Computers');
        $socialTiktok = setting('social_tiktok', 'https://tiktok.com/@amstroom_computers');
        $socialTiktokHandle = setting('social_tiktok_handle', 'Amstroom Computers');
        $socialTwitter = setting('social_twitter', '');
        $socialTwitterHandle = setting('social_twitter_handle', '');
        $socialLinkedin = setting('social_linkedin', '');
        $socialLinkedinHandle = setting('social_linkedin_handle', '');
        $socialYoutube = setting('social_youtube', '');
        $socialYoutubeHandle = setting('social_youtube_handle', '');
        $sliderInterval = setting('slider_interval', '5');

        return view('admin.settings.edit', compact(
            'officeName', 'slogan', 'logoPath',
            'contactAddress', 'contactPhone', 'contactWhatsapp', 'contactEmail', 'contactHours',
            'googleMapIframe',
            'socialInstagram', 'socialInstagramHandle',
            'socialFacebook', 'socialFacebookHandle',
            'socialTiktok', 'socialTiktokHandle',
            'socialTwitter', 'socialTwitterHandle',
            'socialLinkedin', 'socialLinkedinHandle',
            'socialYoutube', 'socialYoutubeHandle',
            'sliderInterval'
        ));
    }

    /**
     * Update company configuration settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'office_name' => 'required|string|max:255',
            'slogan' => 'required|string|max:255',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:2048',
            'contact_address' => 'required|string|max:1000',
            'contact_phone' => 'required|string|max:100',
            'contact_whatsapp' => 'required|string|max:100',
            'contact_email' => 'required|email|max:255',
            'contact_hours' => 'required|string|max:1000',
            'google_map_iframe' => 'nullable|string|max:2000',
            'social_instagram' => 'nullable|url|max:255',
            'social_instagram_handle' => 'nullable|string|max:100',
            'social_facebook' => 'nullable|url|max:255',
            'social_facebook_handle' => 'nullable|string|max:100',
            'social_tiktok' => 'nullable|url|max:255',
            'social_tiktok_handle' => 'nullable|string|max:100',
            'social_twitter' => 'nullable|url|max:255',
            'social_twitter_handle' => 'nullable|string|max:100',
            'social_linkedin' => 'nullable|url|max:255',
            'social_linkedin_handle' => 'nullable|string|max:100',
            'social_youtube' => 'nullable|url|max:255',
            'social_youtube_handle' => 'nullable|string|max:100',
            'slider_interval' => 'required|integer|min:1|max:60',
        ]);

        $changes = [];

        // Save all text settings and track changes
        $textSettings = [
            'office_name', 'slogan', 'contact_address', 'contact_phone', 'contact_whatsapp',
            'contact_email', 'contact_hours', 'google_map_iframe',
            'social_instagram', 'social_instagram_handle',
            'social_facebook', 'social_facebook_handle',
            'social_tiktok', 'social_tiktok_handle',
            'social_twitter', 'social_twitter_handle',
            'social_linkedin', 'social_linkedin_handle',
            'social_youtube', 'social_youtube_handle',
            'slider_interval'
        ];

        foreach ($textSettings as $key) {
            $newValue = $request->input($key) ?? ''; // Default empty string for nullable fields
            $oldValue = setting($key, '');
            
            if ($oldValue !== $newValue) {
                \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $newValue]);
                \Illuminate\Support\Facades\Cache::forget("settings:{$key}");
                $changes[$key] = ['before' => $oldValue, 'after' => $newValue];
            }
        }

        // Track logo file upload
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = 'logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure settings uploads directory exists
            $uploadPath = public_path('uploads/settings');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $newLogoPath = 'uploads/settings/' . $filename;
            
            $oldLogoPath = setting('logo_path', 'images/logo.png');
            \App\Models\Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $newLogoPath]);
            \Illuminate\Support\Facades\Cache::forget('settings:logo_path');
            
            $changes['logo_path'] = ['before' => $oldLogoPath, 'after' => $newLogoPath];
        }

        if (!empty($changes)) {
            // Log setting update action
            \App\Models\ActivityLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'updated',
                'model_type' => \App\Models\Setting::class,
                'model_id' => null,
                'details' => [
                    'message' => 'Updated system settings configurations.',
                    'before' => collect($changes)->map(fn($c) => $c['before'])->toArray(),
                    'after' => collect($changes)->map(fn($c) => $c['after'])->toArray(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        return redirect()->route('admin.settings.edit')->with('success', 'System settings updated successfully!');
    }

    /**
     * Display a listing of the sliders.
     */
    public function indexSliders()
    {
        $sliders = \App\Models\Slider::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new slider.
     */
    public function createSlider()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created slider in storage.
     */
    public function storeSlider(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'primary_btn_text' => 'nullable|string|max:50',
            'primary_btn_url' => 'nullable|string|max:255',
            'secondary_btn_text' => 'nullable|string|max:50',
            'secondary_btn_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'overlay_opacity' => 'required|numeric|min:0|max:1',
            'status' => 'required|boolean',
            'sort_order' => 'required|integer',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'slider_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/sliders');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $filename);
            $validated['image_path'] = 'uploads/sliders/' . $filename;
        }

        unset($validated['image_file']);
        $slider = \App\Models\Slider::create($validated);

        // Log action
        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'created',
            'model_type' => \App\Models\Slider::class,
            'model_id' => $slider->id,
            'details' => ['message' => "Created a new hero slide: {$slider->title}."],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide created successfully!');
    }

    /**
     * Show the form for editing the specified slider.
     */
    public function editSlider(\App\Models\Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified slider in storage.
     */
    public function updateSlider(Request $request, \App\Models\Slider $slider)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'primary_btn_text' => 'nullable|string|max:50',
            'primary_btn_url' => 'nullable|string|max:255',
            'secondary_btn_text' => 'nullable|string|max:50',
            'secondary_btn_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'overlay_opacity' => 'required|numeric|min:0|max:1',
            'status' => 'required|boolean',
            'sort_order' => 'required|integer',
        ]);

        if ($request->hasFile('image_file')) {
            // Delete old file if it exists
            if ($slider->image_path && file_exists(public_path($slider->image_path))) {
                @unlink(public_path($slider->image_path));
            }

            $file = $request->file('image_file');
            $filename = 'slider_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/sliders');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $filename);
            $validated['image_path'] = 'uploads/sliders/' . $filename;
        }

        unset($validated['image_file']);
        $slider->update($validated);

        // Log action
        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'updated',
            'model_type' => \App\Models\Slider::class,
            'model_id' => $slider->id,
            'details' => ['message' => "Updated hero slide: {$slider->title}."],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide updated successfully!');
    }

    /**
     * Remove the specified slider from storage.
     */
    public function deleteSlider(Request $request, \App\Models\Slider $slider)
    {
        // Delete image file if it exists
        if ($slider->image_path && file_exists(public_path($slider->image_path))) {
            @unlink(public_path($slider->image_path));
        }

        $title = $slider->title;
        $slider->delete();

        // Log action
        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'deleted',
            'model_type' => \App\Models\Slider::class,
            'model_id' => null,
            'details' => ['message' => "Deleted hero slide: {$title}."],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide deleted successfully!');
    }

    /**
     * Show Forgot Password Request form.
     */
    public function showForgotPassword()
    {
        return view('admin.passwords.email');
    }

    /**
     * Generate 6-digit OTP code and send code email.
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'No account associated with this email address was found.'
        ]);

        $email = $request->email;
        $code = strval(rand(100000, 999999));

        // Save code in password_reset_tokens table
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $code,
                'created_at' => now()
            ]
        );

        // Send validation code via Mail
        try {
            \Illuminate\Support\Facades\Mail::send('emails.reset_code', ['code' => $code], function($message) use ($email) {
                $message->to($email)
                        ->subject('AMSTROOM COMPUTERS - Password Reset Verification Code');
            });
        } catch (\Exception $e) {
            // Log error but proceed to help testing or show in local log
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
        }

        // Set email in session and route to verification page
        session(['reset_email' => $email]);

        // Log the activity
        try {
            $user = \App\Models\User::where('email', $email)->first();
            \App\Models\ActivityLog::create([
                'user_id' => $user->id ?? null,
                'action' => 'updated',
                'model_type' => \App\Models\User::class,
                'model_id' => $user->id ?? null,
                'details' => ['message' => 'Requested password reset verification code.'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        } catch (\Exception $ex) {}

        return redirect()->route('admin.password.verify.show')->with('success', 'A 6-digit verification code has been sent to your email address.');
    }

    /**
     * Show verification code entry form.
     */
    public function showVerifyCode()
    {
        if (!session('reset_email')) {
            return redirect()->route('admin.password.request')->with('error', 'Please enter your email first.');
        }

        return view('admin.passwords.verify');
    }

    /**
     * Validate the submitted verification code.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('admin.password.request')->with('error', 'Session expired. Please request a new code.');
        }

        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        // Check matching and expiration (15 minutes)
        if (!$record || $record->token !== $request->code || now()->diffInMinutes($record->created_at) > 15) {
            return back()->withErrors(['code' => 'The verification code is invalid or has expired.'])->withInput();
        }

        // Save validation state
        session(['reset_code_validated' => true]);

        return redirect()->route('admin.password.reset.show');
    }

    /**
     * Show New Password resetting form.
     */
    public function showResetPassword()
    {
        if (!session('reset_email') || !session('reset_code_validated')) {
            return redirect()->route('admin.password.request')->with('error', 'Session verification expired. Please start over.');
        }

        return view('admin.passwords.reset');
    }

    /**
     * Save the new validated password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $email = session('reset_email');
        if (!$email || !session('reset_code_validated')) {
            return redirect()->route('admin.password.request')->with('error', 'Session verification expired. Please start over.');
        }

        $user = \App\Models\User::where('email', $email)->firstOrFail();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        // Clear tokens and session
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['reset_email', 'reset_code_validated']);

        // Log the activity
        try {
            \App\Models\ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'updated',
                'model_type' => \App\Models\User::class,
                'model_id' => $user->id,
                'details' => ['message' => 'Successfully reset account password via email verification code.'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        } catch (\Exception $ex) {}

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please sign in with your new credentials.');
    }

    /**
     * Display the homepage components manager dashboard.
     */
    public function indexHomepage()
    {
        $services = Service::orderBy('sort_order')->get();
        $whyChooses = WhyChoose::orderBy('sort_order')->get();
        $stats = Stat::orderBy('sort_order')->get();
        
        return view('admin.homepage.index', compact('services', 'whyChooses', 'stats'));
    }

    // ==========================================
    // SERVICES CRUD
    // ==========================================

    public function createService()
    {
        return view('admin.homepage.services.create');
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        Service::create($validated);

        return redirect()->route('admin.homepage.index')->with('success', 'Service added successfully!');
    }

    public function editService(Service $service)
    {
        return view('admin.homepage.services.edit', compact('service'));
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $service->update($validated);

        return redirect()->route('admin.homepage.index')->with('success', 'Service updated successfully!');
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.homepage.index')->with('success', 'Service deleted successfully!');
    }

    // ==========================================
    // WHY CHOOSES CRUD
    // ==========================================

    public function createWhyChoose()
    {
        return view('admin.homepage.why-chooses.create');
    }

    public function storeWhyChoose(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        WhyChoose::create($validated);

        return redirect()->route('admin.homepage.index')->with('success', 'Feature added successfully!');
    }

    public function editWhyChoose(WhyChoose $whyChoose)
    {
        return view('admin.homepage.why-chooses.edit', compact('whyChoose'));
    }

    public function updateWhyChoose(Request $request, WhyChoose $whyChoose)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $whyChoose->update($validated);

        return redirect()->route('admin.homepage.index')->with('success', 'Feature updated successfully!');
    }

    public function deleteWhyChoose(WhyChoose $whyChoose)
    {
        $whyChoose->delete();
        return redirect()->route('admin.homepage.index')->with('success', 'Feature deleted successfully!');
    }

    // ==========================================
    // STATS CRUD
    // ==========================================

    public function createStat()
    {
        return view('admin.homepage.stats.create');
    }

    public function storeStat(Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        Stat::create($validated);

        return redirect()->route('admin.homepage.index')->with('success', 'Stat added successfully!');
    }

    public function editStat(Stat $stat)
    {
        return view('admin.homepage.stats.edit', compact('stat'));
    }

    public function updateStat(Request $request, Stat $stat)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $stat->update($validated);

        return redirect()->route('admin.homepage.index')->with('success', 'Stat updated successfully!');
    }

    public function deleteStat(Stat $stat)
    {
        $stat->delete();
        return redirect()->route('admin.homepage.index')->with('success', 'Stat deleted successfully!');
    }

    // ==========================================
    // PAYMENT METHODS CRUD
    // ==========================================

    public function indexPaymentMethods()
    {
        $paymentMethods = PaymentMethod::all();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function createPaymentMethod()
    {
        return view('admin.payment-methods.create');
    }

    public function storePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/payment_logos'), $imageName);
            $logoPath = 'uploads/payment_logos/' . $imageName;
        }

        PaymentMethod::create([
            'name' => $validated['name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'logo_path' => $logoPath,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment Method created successfully!');
    }

    public function editPaymentMethod(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function updatePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $logoPath = $paymentMethod->logo_path;
        if ($request->hasFile('logo')) {
            // Delete old file if exists
            if ($logoPath && file_exists(public_path($logoPath))) {
                @unlink(public_path($logoPath));
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/payment_logos'), $imageName);
            $logoPath = 'uploads/payment_logos/' . $imageName;
        }

        $paymentMethod->update([
            'name' => $validated['name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'logo_path' => $logoPath,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment Method updated successfully!');
    }

    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->logo_path && file_exists(public_path($paymentMethod->logo_path))) {
            @unlink(public_path($paymentMethod->logo_path));
        }
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment Method deleted successfully!');
    }
}
