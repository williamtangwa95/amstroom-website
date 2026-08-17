<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ContactMessage;
use App\Models\ProductRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with products.
     */
    public function index()
    {
        $products = Product::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        $sliders = \App\Models\Slider::where('status', true)->orderBy('sort_order')->get();
        $services = \App\Models\Service::orderBy('sort_order')->get();
        $whyChooses = \App\Models\WhyChoose::orderBy('sort_order')->get();
        $stats = \App\Models\Stat::orderBy('sort_order')->get();
        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();
        
        return view('home', compact('products', 'sliders', 'services', 'whyChooses', 'stats', 'paymentMethods'));
    }

    /**
     * Handle the contact form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($validated);

        return redirect()->route('home')->with('success', 'Your message has been sent successfully! We will contact you soon.');
    }

    /**
     * Handle the product request or cart checkout submission.
     */
    public function submitProductRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'request_type' => 'required|string|in:custom,cart',
            'details' => 'required|string',
            'total_price' => 'nullable|numeric|min:0',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'reference_number' => 'nullable|string|max:255',
        ]);

        $validated['total_price'] = $validated['total_price'] ?? 0;
        $validated['status'] = 'pending';

        ProductRequest::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your product request has been submitted successfully! We will contact you soon.'
            ]);
        }

        return redirect()->route('home')->with('success', 'Your product request has been submitted successfully! We will contact you soon.');
    }
}
