<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Your trusted destination for quality laptops, desktop computers, accessories, Windows & software installation, security installations (CCTV, Electric Fence), computer repairs, and IT support in Morogoro, Tanzania.')">
    <meta name="keywords" content="Amstroom Computers, laptops Morogoro, computer repair Morogoro, laptop store Tanzania, cheap laptops Tanzania, desktop computers Morogoro, software installation Morogoro, electric fence Morogoro, CCTV camera installation Morogoro, IT support Tanzania">
    <meta name="author" content="{{ setting('office_name', 'AMSTROOM COMPUTERS') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', setting('office_name', 'AMSTROOM COMPUTERS') . ' | ' . setting('slogan', 'Technology Innovations'))">
    <meta property="og:description" content="Your trusted destination for quality laptops, desktop computers, accessories, software installation, repairs, and professional security/IT support services in Tanzania.">
    <meta property="og:image" content="{{ asset(setting('logo_path', 'images/logo.png')) }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', setting('office_name', 'AMSTROOM COMPUTERS') . ' | ' . setting('slogan', 'Technology Innovations'))">
    <meta property="twitter:description" content="Your trusted destination for quality laptops, desktop computers, accessories, software installation, repairs, and professional security/IT support services in Tanzania.">
    <meta property="twitter:image" content="{{ asset(setting('logo_path', 'images/logo.png')) }}">

    <title>@yield('title', setting('office_name', 'AMSTROOM COMPUTERS') . ' | ' . setting('slogan', 'Technology Innovations'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset(setting('logo_path', 'images/logo.png')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>

<body>

    <div class="top-bar">
        🚚 Delivery Available | ✅ 30+ Days Warranty | 💻 Tested & Ready To Use
    </div>

    <header>
        <nav>
            <div class="logo">
                <img src="{{ asset(setting('logo_path', 'images/logo.png')) }}" alt="Logo">
                <div class="logo-text">
                    <h2>{{ setting('office_name', 'AMSTROOM COMPUTERS') }}</h2>
                    <span>{{ setting('slogan', 'Technology Innovations • Fast & Reliable') }}</span>
                </div>
            </div>

            <!-- Hamburger Toggle Button -->
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <ul id="navMenu">
                <li><a href="{{ route('home') }}#home">Home</a></li>
                <li><a href="{{ route('home') }}#products">Products</a></li>
                <li><a href="{{ route('home') }}#services">Services</a></li>
                <li><a href="{{ route('home') }}#contact">Contact</a></li>
                <li><a href="{{ route('login') }}"><i class="fa fa-user-lock"></i> Admin</a></li>
            </ul>


        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} AMSTROOM COMPUTERS | Technology Innovations | Fast & Reliable
    </footer>

    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '255710635173')) }}" class="whatsapp" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Toast Notifications -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
        <div class="toast toast-success show" id="successToast">
            <i class="fas fa-check-circle" style="color: var(--gold); font-size: 20px;"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
    </div>

    <script>
        // Auto-hide toast after 5 seconds
        window.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('successToast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 5000);
            }
        });

        // Global Nav Search Logic
        const navSearch = document.getElementById('navProductSearch');
        if (navSearch) {
            navSearch.addEventListener('input', function(e) {
                const query = e.target.value;

                // If not on homepage, redirect with search query parameter
                if (window.location.pathname !== '/' && window.location.pathname !== '/index.php' && !window.location.pathname.endsWith('/')) {
                    window.location.href = '/?search=' + encodeURIComponent(query) + '#products';
                    return;
                }

                // Scroll to products section smoothly if not already viewing it
                const productsSection = document.getElementById('products');
                if (productsSection) {
                    const rect = productsSection.getBoundingClientRect();
                    // Scroll if products section is not near the top of viewport
                    if (rect.top > 100 || rect.bottom < 100) {
                        productsSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }

                // Sync query to home search input and trigger its input event
                const homeSearch = document.getElementById('productSearch');
                if (homeSearch) {
                    homeSearch.value = query;
                    homeSearch.dispatchEvent(new Event('input'));
                }
            });

            // Check URL parameters for search on page load
            window.addEventListener('DOMContentLoaded', () => {
                const params = new URLSearchParams(window.location.search);
                const searchQuery = params.get('search');
                if (searchQuery) {
                    navSearch.value = searchQuery;
                    const homeSearch = document.getElementById('productSearch');
                    if (homeSearch) {
                        homeSearch.value = searchQuery;
                        homeSearch.dispatchEvent(new Event('input'));

                        // Scroll to products after a slight delay for page rendering
                        setTimeout(() => {
                            const productsSection = document.getElementById('products');
                            if (productsSection) {
                                productsSection.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            }
                        }, 300);
                    }
                }
            });
        }

        // Mobile Navigation Toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');

        if (navToggle && navMenu) {
            navToggle.addEventListener('click', () => {
                navToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
            });

            // Close navigation when clicking a link
            navMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    navToggle.classList.remove('active');
                    navMenu.classList.remove('active');
                });
            });
        }
    </script>
    @yield('scripts')
</body>

</html>