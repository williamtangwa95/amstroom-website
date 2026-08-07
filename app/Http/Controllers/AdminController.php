<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Category;
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
        
        $recentMessages = ContactMessage::latest()->take(5)->get();
        $recentProducts = Product::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'productsCount', 
            'messagesCount', 
            'usersCount', 
            'recentMessages', 
            'recentProducts'
        ));
    }

    /**
     * Display the products catalog listing.
     */
    public function indexProducts()
    {
        $products = Product::all();
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
     * Log the admin out.
     */
    public function logout(Request $request)
    {
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_from_price' => 'boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $validated['is_from_price'] = $request->has('is_from_price');

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_from_price' => 'boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $validated['is_from_price'] = $request->has('is_from_price');

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

    /**
     * Delete the specified product from the database.
     */
    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Product deleted successfully!');
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
}
