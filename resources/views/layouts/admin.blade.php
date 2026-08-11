<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') | {{ setting('office_name', 'AMSTROOM COMPUTERS') }}</title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset(setting('logo_path', 'images/logo.png')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- DataTables CSS CDN & Responsive Extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Modern thin scrollbar for sidebar menu */
        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- MOBILE NAVIGATION HEADER (Picture 1 style) -->
    <div class="admin-mobile-header" style="display: none; background: white; border-bottom: 1px solid #e2e8f0; height: 75px; padding: 10px 20px; box-sizing: border-box; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; right: 0; z-index: 1001; box-shadow: 0 3px 10px rgba(0,0,0,0.05);">
        <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
            <img src="{{ asset(setting('logo_path', 'images/logo.png')) }}" alt="Logo" style="width: 45px; height: 45px; object-fit: contain; flex-shrink: 0;">
            <div style="display: flex; flex-direction: column; text-align: left; min-width: 0;">
                <h2 style="color: #0B4FB5; font-size: 16px; margin: 0; font-weight: 800; line-height: 1.1; letter-spacing: -0.2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ setting('office_name', 'AMSTROOM COMPUTERS') }}</h2>
                <span style="color: #39A8E8; font-size: 10px; font-weight: 600; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ setting('slogan', 'Technology Innovations • Fast & Reliable') }}</span>
            </div>
        </div>
        <button id="mobileNavToggle" style="flex-shrink: 0; background: none; border: none; color: #0B4FB5; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; outline: none;">
            <i class="fas fa-bars" id="toggleIcon"></i>
        </button>
        
        <!-- Dropdown Menu Links (Vertical, centered, white background, matches Picture 1 layout) -->
        <div id="mobileDropdownMenu" style="display: none; flex-direction: column; width: 100%; position: absolute; top: 75px; left: 0; background: white; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); padding: 25px 20px; gap: 12px; z-index: 1000; border-top: 1px solid #f1f5f9; box-sizing: border-box; max-height: calc(100vh - 75px); overflow-y: auto;">
            
            <!-- User Profile Quick Info -->
            <div style="display: flex; align-items: center; gap: 10px; padding: 10px 0 15px 0; border-bottom: 1px solid #f1f5f9; margin-bottom: 10px;">
                <div style="background: #0B4FB5; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 13px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="text-align: left;">
                    <h4 style="font-size: 13px; font-weight: 600; margin: 0; color: #333;">{{ auth()->user()->name }} ({{ auth()->user()->role }})</h4>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="mobile-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="mobile-menu-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">Product Catalog</a>
            <a href="{{ route('admin.categories.index') }}" class="mobile-menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Product Categories</a>
            <a href="{{ route('admin.inquiries.index') }}" class="mobile-menu-link {{ request()->routeIs('admin.inquiries.index') ? 'active' : '' }}">Customer Inquiries</a>
            <a href="{{ route('admin.requests.index') }}" class="mobile-menu-link {{ request()->routeIs('admin.requests.index') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                <span>Product Requests & Orders</span>
                @if($pendingReqCount = \App\Models\ProductRequest::where('status', 'pending')->count())
                    <span style="background: var(--gold); color: black; font-size: 11px; padding: 2px 7px; border-radius: 10px; font-weight: 700;">{{ $pendingReqCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="mobile-menu-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}">My Profile</a>
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="mobile-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Manage Users</a>
                <a href="{{ route('admin.logs.visitors') }}" class="mobile-menu-link {{ request()->routeIs('admin.logs.visitors') ? 'active' : '' }}">Visitor Analytics</a>
                <a href="{{ route('admin.logs.activity') }}" class="mobile-menu-link {{ request()->routeIs('admin.logs.activity') ? 'active' : '' }}">User Activity Logs</a>
                <a href="{{ route('admin.sliders.index') }}" class="mobile-menu-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">Hero Slider</a>
                <a href="{{ route('admin.homepage.index') }}" class="mobile-menu-link {{ request()->routeIs('admin.homepage.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.why-chooses.*') || request()->routeIs('admin.stats.*') ? 'active' : '' }}">Homepage Content</a>
                <a href="{{ route('admin.settings.edit') }}" class="mobile-menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">System Settings</a>
            @endif
            
            <a href="{{ route('home') }}" target="_blank" class="mobile-menu-link" style="border-top: 1px solid #f1f5f9; padding-top: 15px; margin-top: 5px; color: #0B4FB5;"><i class="fas fa-external-link-alt"></i> View Website</a>
            
            <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0; width: 100%;">
                @csrf
                <button type="submit" class="mobile-menu-link" style="color: #dc3545; border: none; background: none; width: 100%; text-align: center; cursor: pointer; font-family: inherit; font-weight: 700; padding: 12px 0;"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="admin-layout" style="display: flex; min-height: 100vh;">
        <!-- LEFT SIDEBAR -->
        <aside class="admin-sidebar" style="width: 260px; background: var(--dark); color: white; position: fixed; height: 100vh; display: flex; flex-direction: column; z-index: 100; transition: 0.3s; box-shadow: 4px 0 10px rgba(0,0,0,0.05);">
            <div class="admin-logo" style="padding: 20px 15px; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <img src="{{ asset(setting('logo_path', 'images/logo.png')) }}" alt="Logo" style="width: 50px; height: 50px; object-fit: contain; padding: 2px; background: white; border-radius: 8px;">
                <div>
                    <h2 style="color: var(--gold); font-size: 16px; margin-bottom: 2px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;">{{ explode(' ', setting('office_name', 'AMSTROOM COMPUTERS'))[0] }}</h2>
                    <span style="font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px;">Admin Portal</span>
                </div>
            </div>
            
            <!-- User Profile Card -->
            <div class="sidebar-user" style="padding: 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.08); background: rgba(0,0,0,0.15);">
                <div style="background: var(--primary); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 16px; border: 2px solid rgba(255,255,255,0.2);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h4 style="font-size: 14px; font-weight: 600; margin: 0; color: white;">{{ auth()->user()->name }}</h4>
                    <span style="font-size: 11px; color: rgba(255,255,255,0.5); text-transform: capitalize;">{{ auth()->user()->role }}</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="sidebar-menu" style="flex: 1; padding: 25px 15px; display: flex; flex-direction: column; gap: 8px; overflow-y: auto;">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                    <i class="fas fa-tachometer-alt" style="width: 20px; font-size: 16px;"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                    <i class="fas fa-laptop-code" style="width: 20px; font-size: 16px;"></i> Product Catalog
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                    <i class="fas fa-tags" style="width: 20px; font-size: 16px;"></i> Product Categories
                </a>
                <a href="{{ route('admin.inquiries.index') }}" class="sidebar-link {{ request()->routeIs('admin.inquiries.index') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                    <i class="fas fa-envelope-open-text" style="width: 20px; font-size: 16px;"></i> Customer Inquiries
                </a>
                <a href="{{ route('admin.requests.index') }}" class="sidebar-link {{ request()->routeIs('admin.requests.index') ? 'active' : '' }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-shopping-basket" style="width: 20px; font-size: 16px;"></i> Requests & Orders
                    </div>
                    @if($pendingReqCount = \App\Models\ProductRequest::where('status', 'pending')->count())
                        <span style="background: var(--gold); color: black; font-size: 11px; padding: 2px 7px; border-radius: 10px; font-weight: 700;">{{ $pendingReqCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="sidebar-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                    <i class="fas fa-user-circle" style="width: 20px; font-size: 16px;"></i> My Profile
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                        <i class="fas fa-users-cog" style="width: 20px; font-size: 16px;"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.logs.visitors') }}" class="sidebar-link {{ request()->routeIs('admin.logs.visitors') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                        <i class="fas fa-chart-bar" style="width: 20px; font-size: 16px;"></i> Visitor Analytics
                    </a>
                    <a href="{{ route('admin.logs.activity') }}" class="sidebar-link {{ request()->routeIs('admin.logs.activity') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                        <i class="fas fa-history" style="width: 20px; font-size: 16px;"></i> User Activity Logs
                    </a>
                    <a href="{{ route('admin.sliders.index') }}" class="sidebar-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                        <i class="fas fa-images" style="width: 20px; font-size: 16px;"></i> Hero Slider
                    </a>
                    <a href="{{ route('admin.homepage.index') }}" class="sidebar-link {{ request()->routeIs('admin.homepage.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.why-chooses.*') || request()->routeIs('admin.stats.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                        <i class="fas fa-home" style="width: 20px; font-size: 16px;"></i> Homepage Content
                    </a>
                    <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                        <i class="fas fa-sliders-h" style="width: 20px; font-size: 16px;"></i> System Settings
                    </a>
                @endif
                <a href="{{ route('home') }}" target="_blank" class="sidebar-link" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 20px;">
                    <i class="fas fa-external-link-alt" style="width: 20px; font-size: 16px;"></i> View Website
                </a>
                
                <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="sidebar-link btn-logout-sidebar" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(220,53,69,0.85); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px; width: 100%; border: none; background: none; text-align: left; cursor: pointer; font-family: inherit;">
                        <i class="fas fa-sign-out-alt" style="width: 20px; font-size: 16px;"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- RIGHT CONTENT AREA -->
        <main class="admin-main" style="flex: 1; margin-left: 260px; padding: 40px; background: var(--light); min-height: 100vh;">
            <!-- Status Notifications -->
            @if(session('success'))
                <div style="background: rgba(40, 167, 69, 0.1); border-left: 4px solid #28a745; padding: 15px; border-radius: 6px; margin-bottom: 20px; color: #28a745; display: flex; align-items: center; gap: 10px; font-size: 14px;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div style="background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 15px; border-radius: 6px; margin-bottom: 20px; color: #dc3545; display: flex; align-items: center; gap: 10px; font-size: 14px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- jQuery and DataTables JS CDN & Responsive Extension -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            // Apply responsive priority properties to headers
            $('.datatable').each(function() {
                const table = $(this);
                
                table.find('thead th').each(function() {
                    const th = $(this);
                    const text = th.text().trim().toLowerCase();
                    
                    // Highest Priority (Keep visible)
                    if (text.includes('name') || text.includes('title') || text.includes('customer') || 
                        text.includes('product') || text.includes('invoice') || text.includes('order') || 
                        text.includes('status')) {
                        th.attr('data-priority', '1');
                    }
                    // Medium Priority
                    else if (text.includes('email') || text.includes('phone') || text.includes('category') || 
                             text.includes('role') || text.includes('amount') || text.includes('price') || 
                             text.includes('qty') || text.includes('quantity')) {
                        th.attr('data-priority', '2');
                    }
                    // Low Priority
                    else if (text.includes('description') || text.includes('desc') || text.includes('address') || 
                             text.includes('note') || text.includes('created') || text.includes('updated') || 
                             text.includes('login') || text.includes('meta')) {
                        th.attr('data-priority', '3');
                    }
                    // Very Low Priority
                    else if (text.includes('id') || text.includes('uuid') || text.includes('ref')) {
                        th.attr('data-priority', '4');
                    }
                    // Keep operations/actions highly visible
                    else if (text.includes('action') || text.includes('operation')) {
                        th.attr('data-priority', '1');
                    }
                });

                // Initialize DataTable with responsive details configuration
                table.DataTable({
                    responsive: {
                        details: {
                            type: 'column',
                            target: 0
                        }
                    },
                    columnDefs: [
                        { className: 'dtr-control', targets: 0 }
                    ],
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    order: [] // Maintain database ordering initially
                });
            });

            // Mobile Menu Dropdown Toggle Logic
            const mobileToggle = $('#mobileNavToggle');
            const mobileMenu = $('#mobileDropdownMenu');
            const mobileIcon = $('#toggleIcon');

            mobileToggle.on('click', function() {
                mobileMenu.slideToggle(300);
                if (mobileIcon.hasClass('fa-bars')) {
                    mobileIcon.removeClass('fa-bars').addClass('fa-times');
                } else {
                    mobileIcon.removeClass('fa-times').addClass('fa-bars');
                }
            });

            // Close dropdown when clicking a link
            $('.mobile-menu-link').on('click', function() {
                mobileMenu.slideUp(300);
                mobileIcon.removeClass('fa-times').addClass('fa-bars');
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
