@extends('layouts.app')

@section('title', setting('office_name', 'AMSTROOM COMPUTERS') . ' | ' . setting('slogan', 'Technology Innovations • Fast & Reliable'))

@section('content')
<!-- HERO SECTION -->
<!-- HERO SLIDER SECTION -->
<section class="hero-slider" id="home" data-interval="{{ (int)setting('slider_interval', 5) * 1000 }}">
    <div class="slider-wrapper">
        @forelse($sliders as $index => $slide)
        <div class="slide {{ $index === 0 ? 'active' : '' }}"
            style="background: linear-gradient(rgba(11,79,181,0.8), rgba(57,168,232,0.75)), url('{{ $slide->image_path ? asset($slide->image_path) : 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1600&q=80' }}') no-repeat center center/cover;"
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
            @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
            <img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80" alt="{{ $product->name }}">
            @endif

            <div class="card-content">
                @if($product->badge)
                <span class="badge">{{ strtoupper($product->badge) }}</span>
                @endif

                <h3>{{ $product->name }}</h3>
                <p>{!! nl2br(e($product->description)) !!}</p>

                <div class="price">
                    @if($product->is_from_price)
                    From
                    @endif
                    TZS {{ number_format($product->price, 0) }}
                </div>

                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '255710635173')) }}?text=Hello%20{{ rawurlencode(setting('office_name', 'AMSTROOM COMPUTERS')) }},%20I%20would%20like%20to%20order%20the%20product:%20{{ urlencode($product->name) }}" class="order-btn" target="_blank">
                    Order Now
                </a>
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
        <div class="service-card">
            <div class="service-icon"><i class="fas fa-laptop"></i></div>
            <h3>Laptop Sales</h3>
            <p>High-quality new and refurbished laptops from Dell, HP, Lenovo, Acer, ASUS, Apple and more.</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fas fa-desktop"></i></div>
            <h3>Desktop Computers</h3>
            <p>Office desktops, gaming PCs, all-in-one computers, and custom-built desktop solutions.</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fab fa-windows"></i></div>
            <h3>Windows & Program (Software) Installations</h3>
            <p>Windows installation, activation, driver setup, formatting, upgrades, and optimization.
                Microsoft Office, Adobe products, antivirus, accounting software, AutoCAD and other essential applications</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fas fa-tools"></i></div>
            <h3>Computer Repair</h3>
            <p>Hardware diagnostics, motherboard repair, screen replacement, keyboard repair, battery replacement, and maintenance.</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fas fa-print"></i></div>
            <h3>Printers & Computer Accessories</h3>
            <p>Printers, cartridges, computer accessories, flash drives, SSDs, HDDs, keyboards, mice and more.</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
            <h3>Security & Surverance</h3>
            <p>Installation of CCTV camera, Electric Fence, Biometrics, Alarm Systems and other security solutions.</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fas fa-database"></i></div>
            <h3>Data Backup & Recovery</h3>
            <p>Professional data backup services for individuals, businesses, schools, and organizations.</p>
        </div>
        <!-- gaming acessories -->
        <div class="service-card">
            <div class="service-icon"><i class="fas fa-gamepad"></i></div>
            <h3>Gaming Acessories</h3>
            <p>Professional Gaming Acessories services for gaming.</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fas fa-headset"></i></div>
            <h3>IT Support</h3>
            <p>Professional IT support services for individuals, businesses, schools, and organizations.</p>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why" id="why">
    <div class="section-title">
        <h2>Why Choose {{ setting('office_name', 'AMSTROOM COMPUTERS') }}?</h2>
        <p>We are committed to delivering quality products, reliable IT solutions, and exceptional customer service you can trust.</p>
    </div>

    <div class="why-grid">
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-shield-halved"></i></div>
            <h3>Quality Guaranteed</h3>
            <p>Every product is carefully tested and verified before delivery, ensuring reliability and excellent performance.</p>
        </div>

        <div class="why-card">
            <div class="why-icon"><i class="fas fa-tags"></i></div>
            <h3>Affordable Prices</h3>
            <p>Competitive prices on laptops, desktops, accessories, and IT services without compromising quality.</p>
        </div>

        <div class="why-card">
            <div class="why-icon"><i class="fas fa-headset"></i></div>
            <h3>Professional Support</h3>
            <p>Friendly and experienced technicians ready to help before and after every purchase.</p>
        </div>

        <div class="why-card">
            <div class="why-icon"><i class="fas fa-truck-fast"></i></div>
            <h3>Fast Delivery</h3>
            <p>We provide quick and secure delivery services to customers across Tanzania.</p>
        </div>

        <div class="why-card">
            <div class="why-icon"><i class="fas fa-award"></i></div>
            <h3>30+ Days Warranty</h3>
            <p>Selected products include a warranty for your confidence and peace of mind.</p>
        </div>

        <div class="why-card">
            <div class="why-icon"><i class="fas fa-users"></i></div>
            <h3>Trusted by Customers</h3>
            <p>Hundreds of satisfied customers continue to choose {{ setting('office_name', 'AMSTROOM COMPUTERS') }} for quality and dependable service.</p>
        </div>
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
</section>
@endsection

@section('scripts')
<script>
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
</style>
@endsection