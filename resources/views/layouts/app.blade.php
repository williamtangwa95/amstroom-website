<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AMSTROOM COMPUTERS | Technology Innovations')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>

<body>

    <div class="top-bar">
        🚚 Delivery Available | ✅ 30 Days Warranty | 💻 Tested & Ready To Use
    </div>

    <header>
        <nav>
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="AMSTROOM COMPUTERS Logo">
                <div class="logo-text">
                    <h2>AMSTROOM COMPUTERS</h2>
                    <span>Technology Innovations • Fast &amp; Reliable</span>
                </div>
            </div>

            <!-- Global Nav Search Bar -->
            <!-- <div class="nav-search" style="position: relative; max-width: 300px; width: 100%; margin: 0 20px;">
                <input type="text" id="navProductSearch" placeholder="Search products..." style="color: #222; border: 1px solid #ddd; background: #f8fafc; padding: 8px 15px 8px 35px; border-radius: 20px; font-size: 14px; width: 100%; outline: none; transition: 0.3s;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--royal); font-size: 13px;"></i>
            </div> -->

            <ul>
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

    <a href="https://wa.me/255710635173" class="whatsapp" target="_blank">
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
    </script>
    @yield('scripts')
</body>

</html>