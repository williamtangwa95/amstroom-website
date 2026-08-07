@extends('layouts.app')

@section('title', 'AMSTROOM COMPUTERS | Technology Innovations')

@section('content')
<!-- HERO SECTION -->
<section class="hero" id="home">
    <div class="hero-content">
        <h1>FAST & RELIABLE TECHNOLOGY SOLUTIONS</h1>
        <p>
            Your trusted destination for laptops, desktops, accessories,
            software installation, repairs and professional IT services.
        </p>
        <div class="hero-buttons">
            <a href="#products" class="btn btn-primary">Browse Products</a>
            <a href="https://wa.me/255710635173" class="btn btn-secondary" target="_blank">WhatsApp Us</a>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="stats">
    <div class="stats-grid">
        <div class="stat">
            <h2>1000+</h2>
            <p>Happy Customers</p>
        </div>
        <div class="stat">
            <h2>30 Days</h2>
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
        <div class="card">
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

                <a href="https://wa.me/255710635173?text=Hello%20AMSTROOM%20COMPUTERS,%20I%20would%20like%20to%20order%20the%20product:%20{{ urlencode($product->name) }}" class="order-btn" target="_blank">
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
            <h3>Windows Installation</h3>
            <p>Windows installation, activation, driver setup, formatting, upgrades, and optimization.</p>
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
            <div class="service-icon"><i class="fas fa-download"></i></div>
            <h3>Software & Programs Installation</h3>
            <p>Microsoft Office, Adobe products, antivirus, accounting software, AutoCAD and other essential applications.</p>
        </div>

        <div class="service-card">
            <div class="service-icon"><i class="fas fa-database"></i></div>
            <h3>Data Backup & Recovery</h3>
            <p>Professional data backup services for individuals, businesses, schools, and organizations.</p>
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
        <h2>Why Choose AMSTROOM COMPUTERS?</h2>
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
            <h3>30 Days Warranty</h3>
            <p>Selected products include a warranty for your confidence and peace of mind.</p>
        </div>

        <div class="why-card">
            <div class="why-icon"><i class="fas fa-users"></i></div>
            <h3>Trusted by Customers</h3>
            <p>Hundreds of satisfied customers continue to choose AMSTROOM COMPUTERS for quality and dependable service.</p>
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

        <!-- Contact Details -->
        <div class="contact-info-grid">
            <div class="contact-item">
                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="details">
                    <h3>Visit Our Store</h3>
                    <p>Shop 101, 2H Plaza<br>Morogoro, Tanzania</p>
                </div>
            </div>

            <div class="contact-item">
                <div class="icon"><i class="fas fa-phone-alt"></i></div>
                <div class="details">
                    <h3>Call / WhatsApp</h3>
                    <a href="tel:+255710635173">+255 710 635 173</a><br>
                    <a href="https://wa.me/255710635173" target="_blank">Chat on WhatsApp</a>
                </div>
            </div>

            <div class="contact-item">
                <div class="icon"><i class="fas fa-envelope"></i></div>
                <div class="details">
                    <h3>Email</h3>
                    <a href="mailto:info@amstroomcomputers.com">info@amstroomcomputers.com</a>
                </div>
            </div>

            <div class="contact-item">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <div class="details">
                    <h3>Business Hours</h3>
                    <p>Monday - Saturday<br>8:00 AM – 7:00 PM</p>
                </div>
            </div>

            <!-- Socials -->
            <div class="contact-social">
                <h3 style="color: #fff; margin-bottom: 15px;">Follow Us</h3>
                <a href="#" class="social instagram">
                    <i class="fab fa-instagram"></i>
                    <span>@amstroom_computers</span>
                </a>
                <a href="#" class="social facebook">
                    <i class="fab fa-facebook-f"></i>
                    <span>Amstroom Computers</span>
                </a>
                <a href="#" class="social tiktok">
                    <i class="fab fa-tiktok"></i>
                    <span>Amstroom Computers</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.getElementById('productSearch').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-grid .card');
        let hasResults = false;

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const desc = card.querySelector('p').textContent.toLowerCase();
            const badge = card.querySelector('.badge') ? card.querySelector('.badge').textContent.toLowerCase() : '';

            if (name.includes(query) || desc.includes(query) || badge.includes(query)) {
                card.style.display = 'flex';
                hasResults = true;
            } else {
                card.style.display = 'none';
            }
        });

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
</script>
@endsection