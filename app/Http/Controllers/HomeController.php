<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with products.
     */
    public function index()
    {
        $products = Product::all();
        $sliders = \App\Models\Slider::where('status', true)->orderBy('sort_order')->get();
        return view('home', compact('products', 'sliders'));
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
}
