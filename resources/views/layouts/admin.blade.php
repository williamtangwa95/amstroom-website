<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') | AMSTROOM COMPUTERS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- DataTables CSS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    @yield('styles')
</head>
<body>

    <div class="admin-layout" style="display: flex; min-height: 100vh;">
        <!-- LEFT SIDEBAR -->
        <aside class="admin-sidebar" style="width: 260px; background: var(--dark); color: white; position: fixed; height: 100vh; display: flex; flex-direction: column; z-index: 100; transition: 0.3s; box-shadow: 4px 0 10px rgba(0,0,0,0.05);">
            <div class="admin-logo" style="padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: center;">
                <h2 style="color: var(--gold); font-size: 20px; margin-bottom: 4px; font-weight: 700; letter-spacing: 0.5px;">AMSTROOM</h2>
                <span style="font-size: 11px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px;">Admin Portal</span>
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
            <nav class="sidebar-menu" style="flex: 1; padding: 25px 15px; display: flex; flex-direction: column; gap: 8px;">
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
                <a href="{{ route('admin.profile.edit') }}" class="sidebar-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                    <i class="fas fa-user-circle" style="width: 20px; font-size: 16px;"></i> My Profile
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14.5px;">
                        <i class="fas fa-users-cog" style="width: 20px; font-size: 16px;"></i> Manage Users
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

    <!-- jQuery and DataTables JS CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [] // Maintain database ordering initially
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
