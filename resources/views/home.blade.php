@extends('layouts.app')

@section('title', setting('office_name', 'AMSTROOM COMPUTERS') . ' | ' . setting('slogan', 'Technology Innovations • Fast & Reliable'))

@section('content')
<!-- HERO SECTION -->
<!-- HERO SLIDER SECTION -->
<section class="hero-slider" id="home" data-interval="{{ (int)setting('slider_interval', 5) * 1000 }}">
    <div class="slider-wrapper">
        @forelse($sliders as $index => $slide)
        <div class="slide {{ $index === 0 ? 'active' : '' }}"
            style="background: linear-gradient(rgba(11,79,181,{{ $slide->overlay_opacity }}), rgba(57,168,232,{{ number_format($slide->overlay_opacity * 0.94, 2) }})), url('{{ $slide->image_path ? asset($slide->image_path) : 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1600&q=80' }}') no-repeat center center/cover;"
            data-slide-index="{{ $index }}">
            <div class="hero-content" style="margin-top: 20px;">
                <h1>{{ $slide->title }}</h1>
                @if($slide->description)
                <p>{{ $slide->description }}</p>
                @endif
                <div class="hero-buttons" style="margin-top: 20px;">
                    @if($slide->primary_btn_text && $slide->primary_btn_url)
                    <a href="{{ $slide->primary_btn_url }}" class="btn btn-primary">{{ $slide->primary_btn_text }}</a>
                    @endif
                    @if($slide->secondary_btn_text && $slide->secondary_btn_url)
                    @php
                    $waUrl = $slide->secondary_btn_url;
                    if (str_contains($waUrl, 'wa.me/255710635173')) {
                    $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '255710635173'));
                    }
                    @endphp
                    <a href="{{ $waUrl }}" class="btn btn-secondary" target="_blank">{{ $slide->secondary_btn_text }}</a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="slide active"
            style="background: linear-gradient(rgba(11,79,181,0.8), rgba(57,168,232,0.75)), url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1600&q=80') no-repeat center center/cover;">
            <div class="hero-content">
                <h1>FAST & RELIABLE TECHNOLOGY SOLUTIONS</h1>
                <p>
                    Your trusted destination for laptops, desktops, accessories,
                    software installation, repairs and professional IT services.
                </p>
                <div class="hero-buttons">
                    <a href="#products" class="btn btn-primary">Browse Products</a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '255710635173')) }}" class="btn btn-secondary" target="_blank">WhatsApp Us</a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Navigation Arrows -->
    @if(count($sliders) > 1)
    <button class="slider-arrow prev-arrow" onclick="changeSlide(-1)" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-arrow next-arrow" onclick="changeSlide(1)" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>

    <!-- Indicators (Dots) -->
    <div class="slider-dots">
        @foreach($sliders as $index => $slide)
        <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></span>
        @endforeach
    </div>
    @endif
</section>

<!-- STATS SECTION -->
<section class="stats">
    <div class="stats-grid">
        @forelse($stats as $stat)
        <div class="stat">
            <h2>{{ $stat->value }}</h2>
            <p>{{ $stat->label }}</p>
        </div>
        @empty
        <div class="stat">
            <h2>500+</h2>
            <p>Happy Customers</p>
        </div>
        <div class="stat">
            <h2>30+ Days</h2>
            <p>Warranty</p>
        </div>
        <div class="stat">
            <h2>24/7</h2>
            <p>Customer Support</p>
        </div>
        <div class="stat">
            <h2>100%</h2>
            <p>Quality Products</p>
        </div>
        @endforelse
    </div>
</section>

<!-- PRODUCTS SECTION -->
<section class="products" id="products">
    <div class="section-title">
        <h2>Featured Products</h2>
        <p>Latest Offers Available In Store</p>
    </div>

    <!-- Live Search Field -->
    <div class="search-container" style="max-width: 500px; margin: -20px auto 40px auto; position: relative; padding: 0 20px;">
        <input type="text" id="productSearch" placeholder="Search for products (e.g. Dell, RAM, Mouse)..." style="color: #222; border: 2px solid #eef2f6; background: #f8fafc; padding: 14px 20px 14px 50px; border-radius: 50px; font-size: 15px; width: 100%; outline: none; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
        <i class="fas fa-search" style="position: absolute; left: 38px; top: 50%; transform: translateY(-50%); color: var(--royal); font-size: 16px;"></i>
    </div>

    <div class="product-grid">
        @forelse($products as $product)
        <div class="card {{ $loop->index >= 6 ? 'hidden-product' : '' }}">
            <div style="position: relative; overflow: hidden; width: 100%; height: 220px;">
                @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; {{ !$product->in_stock ? 'filter: grayscale(1) opacity(0.6);' : '' }}">
                @else
                <img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; {{ !$product->in_stock ? 'filter: grayscale(1) opacity(0.6);' : '' }}">
                @endif
                @if(!$product->in_stock)
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(9, 32, 58, 0.45); color: white; font-weight: 800; font-size: 16px; letter-spacing: 1px; text-transform: uppercase; z-index: 2;">Out of Stock</div>
                @endif
            </div>

            <div class="card-content">
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @if($product->badge)
                    <span class="badge">{{ strtoupper($product->badge) }}</span>
                    @endif
                    @if(!$product->in_stock)
                    <span class="badge" style="background: #dc3545;">OUT OF STOCK</span>
                    @endif
                </div>

                <h3>{{ $product->name }}</h3>
                <p>{!! nl2br(e($product->description)) !!}</p>

                <div class="price">
                    @if($product->is_from_price)
                    From
                    @endif
                    TZS {{ number_format($product->price, 0) }}
                </div>

                <div style="display: flex; gap: 10px; margin-top: auto; width: 100%;">
                    @if($product->in_stock)
                        <button class="order-btn add-to-cart-btn" style="flex: 1; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;" onclick="addToCart(this)" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80' }}">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    @else
                        <button class="order-btn" style="flex: 1; border: none; background: #64748b; color: white; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 8px; opacity: 0.8;" disabled>
                            <i class="fas fa-ban"></i> Out of Stock
                        </button>
                    @endif

                    @php
                        $waMsg = $product->in_stock 
                            ? "Hello " . setting('office_name', 'AMSTROOM COMPUTERS') . ", I would like to order the product: " . $product->name
                            : "Hello " . setting('office_name', 'AMSTROOM COMPUTERS') . ", I would like to inquire about the out of stock product: " . $product->name . ". When will it be back in stock?";
                    @endphp
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '255710635173')) }}?text={{ rawurlencode($waMsg) }}" class="order-btn" style="background: transparent; border: 2px solid var(--gold); color: var(--gold); display: flex; align-items: center; justify-content: center; width: 48px; padding: 0; flex-shrink: 0;" target="_blank" title="{{ $product->in_stock ? 'Order directly via WhatsApp' : 'Inquire via WhatsApp' }}">
                        <i class="fab fa-whatsapp" style="font-size: 20px; margin: 0; color: inherit;"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
            <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 15px; color: var(--royal);"></i>
            <p>No products available in store right now. Check back soon!</p>
        </div>
        @endforelse
    </div>

    @if(count($products) > 6)
    <div class="view-more-container" style="text-align: center; margin-top: 40px;">
        <button id="viewMoreBtn" class="btn btn-primary" onclick="toggleViewMore()">
            View More Products <i class="fas fa-chevron-down" style="margin-left: 8px;"></i>
        </button>
    </div>
    @endif
</section>

<!-- PRODUCT REQUEST SECTION -->
<section class="product-request-section" id="request-product">
    <div class="product-request-container">
        <div class="product-request-info">
            <h2>Can't find the product you're looking for?</h2>
            <p>
                At {{ setting('office_name', 'AMSTROOM COMPUTERS') }}, we source high-quality laptops, custom desktops, and accessories tailored exactly to your requirements. 
                Fill out this request form with your desired specifications (processor, RAM, storage, brand, budget, etc.), and our sales team will find it for you!
            </p>
            <div class="product-request-benefits">
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-check"></i></div>
                    <div class="benefit-text">
                        <h4>Custom Configurations</h4>
                        <p>Get computers customized with exactly the RAM, SSD, or graphics cards you need.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-search-dollar"></i></div>
                    <div class="benefit-text">
                        <h4>Best Market Pricing</h4>
                        <p>We source directly from leading importers to offer you the most competitive rates.</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-shipping-fast"></i></div>
                    <div class="benefit-text">
                        <h4>Secure Logistics &amp; Delivery</h4>
                        <p>We handle safe packaging and quick shipping directly to Morogoro or anywhere in Tanzania.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-request-form-wrapper">
            <h3>Submit a Product Request</h3>
            
            <form id="productRequestForm" action="{{ route('product-request.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="request_type" value="custom">
                
                <div class="form-group">
                    <label for="req_name">Full Name *</label>
                    <input type="text" name="name" id="req_name" class="form-control" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="req_phone">Phone / WhatsApp *</label>
                    <input type="text" name="phone" id="req_phone" class="form-control" placeholder="e.g. +255 710 635 173" required>
                </div>
                
                <div class="form-group">
                    <label for="req_email">Email Address (Optional)</label>
                    <input type="email" name="email" id="req_email" class="form-control" placeholder="Enter your email address">
                </div>
                
                <div class="form-group">
                    <label for="req_details">Specifications / What product are you looking for? *</label>
                    <textarea name="details" id="req_details" rows="5" class="form-control" placeholder="Describe the laptop, desktop, accessory or service you need. Include brands, specifications, model numbers, budget, etc." required></textarea>
                </div>
                
                <button type="submit" class="submit-btn" style="width: 100%;">
                    Submit Request <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="services" id="services">
    <div class="section-title">
        <h2>Our Professional Services</h2>
        <p>
            We provide complete computer sales, repair, installation, and IT solutions
            for individuals, businesses, schools, and organizations.
        </p>
    </div>

    <div class="service-grid">
        @forelse($services as $service)
        <div class="service-card">
            <div class="service-icon"><i class="{{ $service->icon }}"></i></div>
            <h3>{{ $service->title }}</h3>
            <p>{!! nl2br(e($service->description)) !!}</p>
        </div>
        @empty
        <div class="service-card">
            <div class="service-icon"><i class="fas fa-laptop"></i></div>
            <h3>Laptop Sales</h3>
            <p>High-quality new and refurbished laptops from Dell, HP, Lenovo, Acer, ASUS, Apple and more.</p>
        </div>
        @endforelse
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why" id="why">
    <div class="section-title">
        <h2>Why Choose {{ setting('office_name', 'AMSTROOM COMPUTERS') }}?</h2>
        <p>We are committed to delivering quality products, reliable IT solutions, and exceptional customer service you can trust.</p>
    </div>

    <div class="why-grid">
        @forelse($whyChooses as $wc)
        <div class="why-card">
            <div class="why-icon"><i class="{{ $wc->icon }}"></i></div>
            <h3>{{ $wc->title }}</h3>
            <p>{!! nl2br(e($wc->description)) !!}</p>
        </div>
        @empty
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-shield-halved"></i></div>
            <h3>Quality Guaranteed</h3>
            <p>Every product is carefully tested and verified before delivery, ensuring reliability and excellent performance.</p>
        </div>
        @endforelse
    </div>
</section>

<!-- CONTACT & GET IN TOUCH -->
<section class="contact" id="contact">
    <div class="section-title">
        <h2>Get In Touch</h2>
        <p>We're here to help you find the perfect technology solution.</p>
    </div>

    <div class="contact-container">
        <!-- TOP ROW: Message Form & Basic Contact Cards -->
        <div class="contact-main-row">
            <!-- Contact Form -->
            <div class="contact-form-wrapper">
                <h3>Send Us a Message</h3>

                @if($errors->any())
                <div style="background: rgba(220, 53, 69, 0.2); padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545; font-size: 14px;">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address (Optional)</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email address" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone / WhatsApp Number *</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g. +255 710 635 173" value="{{ old('phone') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea name="message" id="message" rows="5" class="form-control" placeholder="Describe how we can help you..." required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="submit-btn">Send Message <i class="fas fa-paper-plane"></i></button>
                </form>
            </div>

            <!-- Basic Contact Cards -->
            @php
            $phoneRaw = preg_replace('/[^0-9+]/', '', setting('contact_phone', '+255710635173'));
            $whatsappRaw = preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '255710635173'));
            $contactPhone = setting('contact_phone', '+255 710 635 173');
            $contactAddress = setting('contact_address', "Shop 101, 2H Plaza\nMorogoro, Tanzania");
            $contactEmail = setting('contact_email', 'info@amstroomcomputers.com');
            $contactHours = setting('contact_hours', "Monday - Saturday\n8:00 AM – 7:00 PM");

            $mapSetting = setting('google_map_iframe', 'https://maps.google.com/maps?q=Shop%20101,%202H%20Plaza,%20Morogoro,%20Tanzania&t=&z=15&ie=UTF8&iwloc=&output=embed');
            $isIframe = str_contains($mapSetting, '<iframe');

                $socials=[
                ['key'=> 'social_instagram', 'icon' => 'fab fa-instagram', 'class' => 'instagram'],
                ['key' => 'social_facebook', 'icon' => 'fab fa-facebook-f', 'class' => 'facebook'],
                ['key' => 'social_tiktok', 'icon' => 'fab fa-tiktok', 'class' => 'tiktok'],
                ['key' => 'social_twitter', 'icon' => 'fab fa-x-twitter', 'class' => 'twitter'],
                ['key' => 'social_linkedin', 'icon' => 'fab fa-linkedin-in', 'class' => 'linkedin'],
                ['key' => 'social_youtube', 'icon' => 'fab fa-youtube', 'class' => 'youtube'],
                ];
                $hasSocials = false;
                foreach ($socials as $s) {
                if (setting($s['key'])) {
                $hasSocials = true;
                }
                }
                @endphp
                <div class="contact-info-grid">
                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="details">
                            <h3>Visit Our Store</h3>
                            <p>{!! nl2br(e($contactAddress)) !!}</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="details">
                            <h3>Call / WhatsApp</h3>
                            <a href="tel:{{ $phoneRaw }}" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                                <i class="fas fa-phone-alt" style="font-size: 13px; color: var(--gold);"></i> {{ $contactPhone }}
                            </a><br>
                            <a href="https://wa.me/{{ $whatsappRaw }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fab fa-whatsapp" style="font-size: 14px; color: #25D366;"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-envelope"></i></div>
                        <div class="details">
                            <h3>Email</h3>
                            <a href="mailto:{{ $contactEmail }}" style="display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-envelope" style="font-size: 13px; color: var(--gold);"></i> {{ $contactEmail }}
                            </a>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon"><i class="fas fa-clock"></i></div>
                        <div class="details">
                            <h3>Business Hours</h3>
                            <p>{!! nl2br(e($contactHours)) !!}</p>
                        </div>
                    </div>
                </div>
        </div>

        <!-- BOTTOM ROW: Google Map & Social Media Links -->
        <div class="contact-bottom-row">
            <!-- Google Map -->
            @if($mapSetting)
            <div class="contact-item map-card" style="flex-direction: column; gap: 15px; align-items: stretch; display: flex; height: 100%; min-height: 380px; margin-bottom: 0;">
                <div style="display: flex; gap: 18px; align-items: center;">
                    <div class="icon"><i class="fas fa-map-marked-alt"></i></div>
                    <div class="details">
                        <h3 style="margin-bottom: 0; font-size: 20px;">Our Location</h3>
                    </div>
                </div>
                <div class="map-container" style="width: 100%; flex: 1; min-height: 260px; border-radius: 10px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(0,0,0,0.15);">
                    @if($isIframe)
                    {!! $mapSetting !!}
                    @else
                    <iframe src="{{ $mapSetting }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>
            </div>
            @endif

            <!-- Socials -->
            @if($hasSocials)
            <div class="contact-social" style="display: flex; flex-direction: column; justify-content: flex-start; height: 100%;">
                <h3 style="color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;"><i class="fas fa-share-alt" style="color: var(--gold);"></i> Follow Us</h3>
                <div class="socials-grid">
                    @foreach($socials as $social)
                    @if($url = setting($social['key']))
                    <a href="{{ $url }}" target="_blank" class="social {{ $social['class'] }}">
                        <i class="{{ $social['icon'] }}"></i>
                        <span>{{ setting($social['key'] . '_handle', 'Amstroom Computers') }}</span>
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
<!-- FLOATING SHOPPING CART BUTTON -->
<button class="cart-floating-btn" id="cartFloatingBtn" onclick="toggleCartDrawer()" style="display: none;">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-badge" id="cartBadge">0</span>
</button>

<!-- CART DRAWER OVERLAY -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCartDrawer()"></div>

<!-- CART DRAWER PANEL -->
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-drawer-header">
        <h3><i class="fas fa-shopping-cart"></i> Shopping Cart</h3>
        <button class="cart-close-btn" onclick="toggleCartDrawer()">&times;</button>
    </div>
    
    <div class="cart-drawer-body">
        <!-- Cart Items list -->
        <div class="cart-items-container" id="cartItemsContainer">
            <!-- Dynamically populated via JS -->
        </div>
        
        <!-- Totals & Checkout -->
        <div class="cart-totals" id="cartTotalsSection" style="display: none;">
            <div class="cart-total-row">
                <span>Subtotal:</span>
                <span id="cartSubtotal">TZS 0</span>
            </div>
            
            <div class="cart-checkout-form">
                <h4>Checkout Details</h4>
                <form id="cartCheckoutForm" action="{{ route('product-request.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_type" value="cart">
                    <input type="hidden" name="total_price" id="cartFormTotalPrice" value="0">
                    <input type="hidden" name="details" id="cartFormDetails" value="">
                    
                    <div class="cart-form-group">
                        <label for="cart_name">Full Name *</label>
                        <input type="text" name="name" id="cart_name" class="cart-form-control" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="cart-form-group">
                        <label for="cart_phone">Phone / WhatsApp Number *</label>
                        <input type="text" name="phone" id="cart_phone" class="cart-form-control" placeholder="e.g. +255 710 635 173" required>
                    </div>
                    
                    <div class="cart-form-group">
                        <label for="cart_email">Email Address (Optional)</label>
                        <input type="email" name="email" id="cart_email" class="cart-form-control" placeholder="Enter your email address">
                    </div>
                    
                    <div class="cart-checkout-actions">
                        <button type="submit" class="btn-checkout-web" id="btnCheckoutWeb">
                            Place Order (Submit Online) <i class="fas fa-paper-plane"></i>
                        </button>
                        <button type="button" class="btn-checkout-wa" onclick="checkoutViaWhatsApp()">
                            Order via WhatsApp <i class="fab fa-whatsapp"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

@section('scripts')
<script>
    // SHOPPING CART LOGIC
    let cart = JSON.parse(localStorage.getItem('amstroom_cart')) || [];

    function saveCart() {
        localStorage.setItem('amstroom_cart', JSON.stringify(cart));
        updateCartUI();
    }

    function toggleCartDrawer() {
        document.getElementById('cartDrawer').classList.toggle('active');
        document.getElementById('cartOverlay').classList.toggle('active');
    }

    function addToCart(btn) {
        const id = parseInt(btn.getAttribute('data-id'));
        const name = btn.getAttribute('data-name');
        const price = parseFloat(btn.getAttribute('data-price'));
        const imageUrl = btn.getAttribute('data-image');

        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                image: imageUrl,
                quantity: 1
            });
        }
        
        saveCart();
        showToast(`Added "${name}" to cart!`);
    }

    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        saveCart();
    }

    function updateQuantity(id, change) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                removeFromCart(id);
            } else {
                saveCart();
            }
        }
    }

    function showToast(message) {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        toast.className = 'toast toast-success show';
        toast.innerHTML = `
            <i class="fas fa-check-circle" style="color: var(--gold); font-size: 20px;"></i>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }

    function updateCartUI() {
        const cartFloatingBtn = document.getElementById('cartFloatingBtn');
        const cartBadge = document.getElementById('cartBadge');
        const cartItemsContainer = document.getElementById('cartItemsContainer');
        const cartTotalsSection = document.getElementById('cartTotalsSection');
        const cartSubtotal = document.getElementById('cartSubtotal');
        
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        if (totalItems > 0) {
            cartFloatingBtn.style.display = 'flex';
            cartBadge.textContent = totalItems;
        } else {
            cartFloatingBtn.style.display = 'none';
        }
        
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="cart-empty-state">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Your cart is empty.</p>
                </div>
            `;
            cartTotalsSection.style.display = 'none';
        } else {
            let html = '';
            let subtotal = 0;
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                html += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">TZS ${itemTotal.toLocaleString()}</div>
                            <div style="font-size: 11.5px; color: #666; margin-top: 2px;">TZS ${item.price.toLocaleString()} each</div>
                        </div>
                        <div class="cart-item-actions">
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <span class="qty-val">${item.quantity}</span>
                                <button type="button" class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                            </div>
                            <button type="button" class="cart-item-remove" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            cartItemsContainer.innerHTML = html;
            cartSubtotal.textContent = `TZS ${subtotal.toLocaleString()}`;
            cartTotalsSection.style.display = 'block';
            
            document.getElementById('cartFormTotalPrice').value = subtotal;
            document.getElementById('cartFormDetails').value = JSON.stringify(cart);
        }
    }

    function checkoutViaWhatsApp() {
        const name = document.getElementById('cart_name').value.trim();
        const phone = document.getElementById('cart_phone').value.trim();
        const email = document.getElementById('cart_email').value.trim();
        
        if (!name || !phone) {
            alert('Please fill in your Name and Phone/WhatsApp number to complete the order.');
            document.getElementById('cart_name').focus();
            return;
        }
        
        let text = `Hello {{ setting('office_name', 'AMSTROOM COMPUTERS') }},\n\n`;
        text += `I would like to place an order for the following items:\n`;
        
        let subtotal = 0;
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            text += `${index + 1}. *${item.name}* (Qty: ${item.quantity}) - TZS ${itemTotal.toLocaleString()}\n`;
        });
        
        text += `\n*Total Amount:* TZS ${subtotal.toLocaleString()}\n\n`;
        text += `*Customer Details:*\n`;
        text += `- Name: ${name}\n`;
        text += `- Phone: ${phone}\n`;
        if (email) {
            text += `- Email: ${email}\n`;
        }
        
        const whatsappNumber = "{{ preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '255710635173')) }}";
        const url = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(text)}`;
        
        const form = document.getElementById('cartCheckoutForm');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            cart = [];
            saveCart();
            toggleCartDrawer();
            form.reset();
            window.open(url, '_blank');
        })
        .catch(err => {
            console.error('Error saving order record:', err);
            window.open(url, '_blank');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateCartUI();
        
        const cartForm = document.getElementById('cartCheckoutForm');
        if (cartForm) {
            cartForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = document.getElementById('btnCheckoutWeb');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Placing Order...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    
                    if (data.success) {
                        cart = [];
                        saveCart();
                        toggleCartDrawer();
                        cartForm.reset();
                        showToast('Order placed successfully! We will contact you soon.');
                    } else {
                        alert(data.message || 'Something went wrong. Please try again.');
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        }

        const requestForm = document.getElementById('productRequestForm');
        if (requestForm) {
            requestForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    
                    if (data.success) {
                        requestForm.reset();
                        showToast('Request submitted successfully! We will contact you soon.');
                    } else {
                        alert(data.message || 'Something went wrong. Please try again.');
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        }
    });

    document.getElementById('productSearch').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-grid .card');
        const viewMoreBtn = document.getElementById('viewMoreBtn');
        let hasResults = false;

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const desc = card.querySelector('p').textContent.toLowerCase();
            const badge = card.querySelector('.badge') ? card.querySelector('.badge').textContent.toLowerCase() : '';

            const matches = name.includes(query) || desc.includes(query) || badge.includes(query);

            if (query === '') {
                card.style.display = ''; // Clear inline styles
                hasResults = true;
            } else {
                if (matches) {
                    card.style.display = 'flex';
                    hasResults = true;
                } else {
                    card.style.display = 'none';
                }
            }
        });

        if (viewMoreBtn) {
            if (query !== '') {
                viewMoreBtn.style.display = 'none';
            } else {
                viewMoreBtn.style.display = 'inline-block';
            }
        }

        // Handle empty state if no products match
        let noResultsEl = document.getElementById('noSearchResults');
        if (!hasResults && query !== '') {
            if (!noResultsEl) {
                noResultsEl = document.createElement('div');
                noResultsEl.id = 'noSearchResults';
                noResultsEl.style.gridColumn = '1/-1';
                noResultsEl.style.textAlign = 'center';
                noResultsEl.style.padding = '40px';
                noResultsEl.style.color = '#666';
                noResultsEl.innerHTML = `
                    <i class="fas fa-search-minus" style="font-size: 50px; margin-bottom: 15px; color: var(--royal);"></i>
                    <p style="margin-top: 10px;">No products match your search "${e.target.value}".</p>
                `;
                document.querySelector('.product-grid').appendChild(noResultsEl);
            } else {
                noResultsEl.style.display = 'block';
                noResultsEl.querySelector('p').textContent = `No products match your search "${e.target.value}".`;
            }
        } else {
            if (noResultsEl) {
                noResultsEl.style.display = 'none';
            }
        }
    });

    function toggleViewMore() {
        const grid = document.querySelector('.product-grid');
        const btn = document.getElementById('viewMoreBtn');

        if (grid.classList.contains('show-all')) {
            grid.classList.remove('show-all');
            btn.innerHTML = 'View More Products <i class="fas fa-chevron-down" style="margin-left: 8px;"></i>';
            document.getElementById('products').scrollIntoView({
                behavior: 'smooth'
            });
        } else {
            grid.classList.add('show-all');
            btn.innerHTML = 'View Less <i class="fas fa-chevron-up" style="margin-left: 8px;"></i>';
        }
    }
</script>

@if(count($sliders) > 1)
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const totalSlides = slides.length;
    const slideTimer = parseInt(document.getElementById('home').getAttribute('data-interval')) || 5000;
    let autoSlideInterval;

    function showSlide(index) {
        if (totalSlides === 0) return;

        if (index >= totalSlides) currentSlide = 0;
        else if (index < 0) currentSlide = totalSlides - 1;
        else currentSlide = index;

        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
    }

    function changeSlide(direction) {
        showSlide(currentSlide + direction);
        resetAutoSlide();
    }

    function goToSlide(index) {
        showSlide(index);
        resetAutoSlide();
    }

    function startAutoSlide() {
        if (slideTimer > 0 && totalSlides > 1) {
            autoSlideInterval = setInterval(() => {
                showSlide(currentSlide + 1);
            }, slideTimer);
        }
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    document.addEventListener('DOMContentLoaded', () => {
        showSlide(0);
        startAutoSlide();
    });
</script>
@endif
@endsection

@section('styles')
<style>
    /* Products view more hidden cards */
    .product-grid .card.hidden-product {
        display: none;
    }

    .product-grid.show-all .card.hidden-product {
        display: flex;
    }

    #viewMoreBtn:hover {
        background: var(--gold) !important;
        color: black !important;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3) !important;
    }

    .map-card iframe {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
    }

    /* Layout grid adjustments */
    .contact-container {
        display: flex !important;
        flex-direction: column !important;
        gap: 40px !important;
    }

    .contact-main-row,
    .contact-bottom-row {
        display: grid !important;
        grid-template-columns: 1.5fr 1fr !important;
        gap: 40px !important;
        width: 100% !important;
        align-items: stretch !important;
    }

    /* Socials grid adjustment */
    .socials-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
        gap: 15px !important;
        width: 100% !important;
    }

    .socials-grid .social {
        margin-bottom: 0 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {

        .contact-main-row,
        .contact-bottom-row {
            grid-template-columns: 1fr !important;
            gap: 30px !important;
        }
    }

    /* HERO SLIDER STYLING */
    .hero-slider {
        position: relative;
        width: 100%;
        min-height: 48vh;
        overflow: hidden;
        color: white;
    }

    .slider-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 48vh;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        min-height: 48vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 50px 20px;
        opacity: 0;
        z-index: 1;
        transition: opacity 0.8s ease-in-out;
    }

    .slide.active {
        opacity: 1;
        z-index: 2;
    }

    /* Slide text animations */
    .slide.active h1 {
        animation: fadeInUp 0.7s ease-out forwards;
    }

    .slide.active p {
        animation: fadeInUp 0.9s ease-out forwards;
    }

    .slide.active .hero-buttons {
        animation: fadeInUp 1.1s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: rgba(0, 0, 0, 0.25);
        color: white;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        outline: none;
    }

    .slider-arrow:hover {
        background: var(--gold);
        color: black;
        transform: translateY(-50%) scale(1.1);
    }

    .prev-arrow {
        left: 20px;
    }

    .next-arrow {
        right: 20px;
    }

    .slider-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        gap: 8px;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .dot.active {
        background: var(--gold);
        transform: scale(1.2);
    }

    /* FLOATING SHOPPING CART BUTTON */
    .cart-floating-btn {
        position: fixed;
        right: 20px;
        bottom: 100px;
        width: 65px;
        height: 65px;
        background: var(--gold);
        color: var(--royal);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        z-index: 999;
        transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        border: none;
        outline: none;
    }

    .cart-floating-btn:hover {
        transform: scale(1.1);
        background: var(--royal);
        color: white;
    }

    .cart-floating-btn .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        font-size: 12px;
        font-weight: 700;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--gold);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        animation: cartBounce 0.3s ease;
    }

    @keyframes cartBounce {
        0% { transform: scale(0.5); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }

    /* CART DRAWER OVERLAY */
    .cart-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(9, 32, 58, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9998;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }

    .cart-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    /* CART DRAWER PANEL */
    .cart-drawer {
        position: fixed;
        top: 0;
        right: -460px;
        width: 450px;
        max-width: 100%;
        height: 100vh;
        background: #ffffff;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cart-drawer.active {
        right: 0;
    }

    .cart-drawer-header {
        padding: 20px 25px;
        background: var(--royal);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .cart-drawer-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        transition: 0.2s;
        outline: none;
    }

    .cart-close-btn:hover {
        color: var(--gold);
        transform: rotate(90deg);
    }

    .cart-drawer-body {
        flex: 1;
        overflow-y: auto;
        padding: 25px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* CART ITEMS LIST */
    .cart-items-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .cart-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #888;
    }

    .cart-empty-state i {
        font-size: 55px;
        color: #ddd;
        margin-bottom: 15px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f5f9;
    }

    .cart-item-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-name {
        font-size: 14.5px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .cart-item-price {
        font-size: 14px;
        font-weight: 700;
        color: var(--royal);
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .qty-control {
        display: flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        overflow: hidden;
        background: #f8fafc;
    }

    .qty-btn {
        border: none;
        background: none;
        width: 26px;
        height: 26px;
        cursor: pointer;
        font-weight: 700;
        font-size: 14px;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .qty-btn:hover {
        background: #e2e8f0;
        color: var(--royal);
    }

    .qty-val {
        width: 30px;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
    }

    .cart-item-remove {
        background: none;
        border: none;
        color: #cbd5e1;
        cursor: pointer;
        font-size: 16px;
        transition: 0.2s;
        padding: 5px;
    }

    .cart-item-remove:hover {
        color: #dc3545;
    }

    /* TOTAL & CHECKOUT FORM */
    .cart-totals {
        border-top: 2px dashed #e2e8f0;
        padding-top: 15px;
        margin-top: 10px;
    }

    .cart-total-row {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
    }

    .cart-checkout-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        background: #f8fafc;
        border: 1px solid #eef2f6;
        border-radius: 12px;
        padding: 20px;
    }

    .cart-checkout-form h4 {
        margin: 0 0 5px 0;
        color: var(--dark);
        font-size: 16px;
        font-weight: 700;
    }

    .cart-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .cart-form-group label {
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        text-align: left;
    }

    .cart-form-control {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
        background: white;
        color: #333;
    }

    .cart-form-control:focus {
        border-color: var(--royal);
        box-shadow: 0 0 0 3px rgba(11, 79, 181, 0.15);
    }

    .cart-checkout-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-checkout-web {
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        border: none;
        font-weight: 700;
        font-size: 15px;
        background: var(--royal);
        color: white;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-checkout-web:hover {
        background: #093c8a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(11, 79, 181, 0.2);
    }

    .btn-checkout-wa {
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        border: 2px solid #25D366;
        font-weight: 700;
        font-size: 15px;
        background: transparent;
        color: #25D366;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-checkout-wa:hover {
        background: #25D366;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.2);
    }

    /* PRODUCT REQUEST SECTION */
    .product-request-section {
        padding: 95px 20px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        text-align: left;
    }

    .product-request-container {
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 50px;
        align-items: start;
    }

    .product-request-info h2 {
        font-size: 36px;
        color: var(--royal);
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 20px;
    }

    .product-request-info p {
        color: #555;
        line-height: 1.8;
        margin-bottom: 30px;
        font-size: 15.5px;
    }

    .product-request-benefits {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .benefit-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(11, 79, 181, 0.08);
        color: var(--royal);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .benefit-text h4 {
        margin: 0 0 5px 0;
        color: var(--dark);
        font-size: 17px;
        font-weight: 700;
        text-align: left;
    }

    .benefit-text p {
        margin: 0;
        color: #555;
        font-size: 14px;
        line-height: 1.6;
        text-align: left;
    }

    .product-request-form-wrapper {
        background: #f8fafc;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }

    .product-request-form-wrapper h3 {
        font-size: 24px;
        color: var(--dark);
        margin-bottom: 30px;
        font-weight: 700;
        text-align: left;
    }

    .product-request-form-wrapper .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .product-request-form-wrapper .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
        color: #475569;
    }

    .product-request-form-wrapper .form-control {
        width: 100% !important;
        padding: 14px 18px !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
        color: #333030 !important;
        font-size: 15px !important;
        outline: none !important;
        transition: 0.3s !important;
        box-sizing: border-box !important;
        display: block !important;
    }

    .product-request-form-wrapper .form-control::placeholder {
        color: #94a3b8 !important;
    }

    .product-request-form-wrapper .form-control:focus {
        border-color: var(--royal) !important;
        box-shadow: 0 0 0 3px rgba(11, 79, 181, 0.15) !important;
        background: #ffffff !important;
        color: #222222 !important;
    }

    .product-request-form-wrapper .submit-btn {
        background: var(--royal);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .product-request-form-wrapper .submit-btn:hover {
        background: #093c8a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(11, 79, 181, 0.2);
    }

    /* RESPONSIVE LAYOUT ADJUSTMENTS */
    @media (max-width: 992px) {
        .product-request-container {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .product-request-info h2 {
            font-size: 30px;
        }
    }

    /* STATS CARD HOVER FADE IN / FADE OUT EFFECT */
    .stat {
        opacity: 0.85;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .stat:hover {
        opacity: 1;
        transform: translateY(-6px);
        box-shadow: 0 15px 30px rgba(11, 79, 181, 0.12);
    }
</style>
@endsection