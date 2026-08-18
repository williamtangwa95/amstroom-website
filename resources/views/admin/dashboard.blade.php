@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Dashboard Overview</h1>
            <p style="color: #666; font-size: 14px;">Welcome back to your administration portal control center.</p>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="metrics-grid" style="margin-top: 25px;">
        <a href="{{ route('admin.products.index') }}" style="text-decoration: none; color: inherit;">
            <div class="metric-card metrics-products" style="transition: 0.3s; cursor: pointer;">
                <div class="metric-info">
                    <h3>Total Products</h3>
                    <p>{{ $productsCount }}</p>
                </div>
                <div class="metric-icon"><i class="fas fa-laptop-code"></i></div>
            </div>
        </a>
        <a href="{{ route('admin.inquiries.index') }}" style="text-decoration: none; color: inherit;">
            <div class="metric-card metrics-messages" style="transition: 0.3s; cursor: pointer;">
                <div class="metric-info">
                    <h3>Customer Inquiries</h3>
                    <p>{{ $messagesCount }}</p>
                </div>
                <div class="metric-icon"><i class="fas fa-envelope-open-text"></i></div>
            </div>
        </a>
        <a href="{{ route('admin.requests.index') }}" style="text-decoration: none; color: inherit;">
            <div class="metric-card" style="background: white; border-top: 5px solid var(--primary); transition: 0.3s; cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); color: #333; height: 100%;">
                <div class="metric-info">
                    <h3 style="color: #64748b; font-size: 14px; margin-bottom: 5px;">Requests & Orders</h3>
                    <p style="font-size: 28px; font-weight: 700; margin: 0; color: var(--dark);">{{ $requestsCount }} <span style="font-size: 13px; font-weight: normal; color: #777;">({{ $pendingRequestsCount }} pending)</span></p>
                </div>
                <div class="metric-icon" style="font-size: 32px; color: var(--primary);"><i class="fas fa-shopping-basket"></i></div>
            </div>
        </a>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}" style="text-decoration: none; color: inherit;">
                <div class="metric-card" style="background: white; border-top: 5px solid #FFC107; transition: 0.3s; cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); color: #333;">
                    <div class="metric-info">
                        <h3 style="color: #64748b; font-size: 14px; margin-bottom: 5px;">User Accounts</h3>
                        <p style="font-size: 28px; font-weight: 700; margin: 0; color: var(--dark);">{{ $usersCount }}</p>
                    </div>
                    <div class="metric-icon" style="font-size: 32px; color: #FFC107;"><i class="fas fa-users-cog"></i></div>
                </div>
            </a>
        @endif
    </div>

    <!-- Quick Actions Panel -->
    <div class="dashboard-section" style="margin-top: 35px;">
        <div class="dashboard-section-header">
            <h2>Quick Actions</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
            <a href="{{ route('admin.products.create') }}" class="btn-action" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration: none; color: var(--primary); font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.background='var(--primary)'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='var(--primary)'">
                <i class="fas fa-plus"></i> Add New Product
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="btn-action" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration: none; color: var(--primary); font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.background='var(--primary)'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='var(--primary)'">
                <i class="fas fa-user-circle"></i> Edit My Profile
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.users.create') }}" class="btn-action" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration: none; color: var(--primary); font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.background='var(--primary)'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='var(--primary)'">
                    <i class="fas fa-user-plus"></i> Register Admin/User
                </a>
            @endif
            <a href="{{ route('home') }}" target="_blank" class="btn-action" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration: none; color: var(--primary); font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.background='var(--primary)'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='var(--primary)'">
                <i class="fas fa-external-link-alt"></i> View Live Site
            </a>
        </div>
    </div>

    <!-- Recent Items Sections -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 40px;">
        <!-- Recent Product Requests & Orders -->
        <div class="dashboard-section" style="margin: 0;">
            <div class="dashboard-section-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Recent Product Requests & Orders</h2>
                <a href="{{ route('admin.requests.index') }}" style="color: var(--primary); text-decoration: none; font-size: 13.5px; font-weight: 600;">View All Requests <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="table-responsive" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); overflow: hidden; padding: 15px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th style="padding: 12px; font-weight: 600;">Customer</th>
                            <th style="padding: 12px; font-weight: 600;">Type</th>
                            <th style="padding: 12px; font-weight: 600;">Summary</th>
                            <th style="padding: 12px; font-weight: 600;">Status</th>
                            <th style="padding: 12px; font-weight: 600;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $req)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="padding: 12px; vertical-align: middle;">
                                    <div style="font-weight: 600; color: var(--dark);">{{ $req->name }}</div>
                                    <div style="font-size: 11.5px; margin-top: 3px; display: flex; align-items: center; gap: 8px;">
                                        <a href="tel:{{ $req->phone }}" style="color: #64748b; text-decoration: none;"><i class="fas fa-phone-alt" style="font-size: 9px;"></i> {{ $req->phone }}</a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $req->phone) }}" target="_blank" style="color: #28a745; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 2px;" title="WhatsApp Chat"><i class="fab fa-whatsapp"></i> Chat</a>
                                    </div>
                                </td>
                                <td style="padding: 12px;">
                                    @if($req->request_type === 'cart')
                                        <span style="background: rgba(40, 167, 69, 0.1); color: #28a745; padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">Cart Order</span>
                                    @else
                                        <span style="background: rgba(11, 79, 181, 0.1); color: #0B4FB5; padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">Custom Request</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; color: #555; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    @if($req->request_type === 'cart')
                                        @php
                                            $items = $req->cart_items;
                                            $summary = '';
                                            if (is_array($items)) {
                                                $names = array_map(function($i) { return $i['quantity'] . 'x ' . $i['name']; }, $items);
                                                $summary = implode(', ', $names);
                                            }
                                        @endphp
                                        {{ Str::limit($summary, 60) }}
                                    @else
                                        {{ Str::limit($req->details, 60) }}
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    @php
                                        $statusColors = [
                                            'pending' => 'background: #ffc107; color: black;',
                                            'in_progress' => 'background: #17a2b8; color: white;',
                                            'completed' => 'background: #28a745; color: white;',
                                            'cancelled' => 'background: #dc3545; color: white;',
                                        ];
                                        $style = $statusColors[$req->status] ?? 'background: #6c757d; color: white;';
                                    @endphp
                                    <span style="{{ $style }} padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: 600; text-transform: capitalize;">{{ str_replace('_', ' ', $req->status) }}</span>
                                </td>
                                <td style="padding: 12px; color: #666; font-size: 13px;">
                                    {{ $req->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #888; padding: 25px;">
                                    No product requests received yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Customer Inquiries -->
        <div class="dashboard-section" style="margin: 0;">
            <div class="dashboard-section-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Recent Customer Inquiries</h2>
                <a href="{{ route('admin.inquiries.index') }}" style="color: var(--primary); text-decoration: none; font-size: 13.5px; font-weight: 600;">View All Inquiries <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="table-responsive" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); overflow: hidden; padding: 15px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th style="padding: 12px; font-weight: 600;">Sender</th>
                            <th style="padding: 12px; font-weight: 600;">Message Preview</th>
                            <th style="padding: 12px; font-weight: 600;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMessages as $msg)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="padding: 12px; font-weight: 600; color: var(--dark);">{{ $msg->name }}</td>
                                <td style="padding: 12px; color: #555; max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ Str::limit($msg->message, 80) }}
                                </td>
                                <td style="padding: 12px; color: #666; font-size: 13px;">
                                    {{ $msg->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: #888; padding: 25px;">
                                    No customer inquiries received yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Products Added -->
        <div class="dashboard-section" style="margin: 0;">
            <div class="dashboard-section-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Recently Added Products</h2>
                <a href="{{ route('admin.products.index') }}" style="color: var(--primary); text-decoration: none; font-size: 13.5px; font-weight: 600;">View Full Catalog <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="table-responsive" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); overflow: hidden; padding: 15px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th style="padding: 12px; font-weight: 600;">Image</th>
                            <th style="padding: 12px; font-weight: 600;">Product</th>
                            <th style="padding: 12px; font-weight: 600;">Price</th>
                            <th style="padding: 12px; font-weight: 600;">Added Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProducts as $prod)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="padding: 8px 12px;">
                                    <img src="{{ $prod->image_url ?? 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80' }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                </td>
                                <td style="padding: 12px; font-weight: 600; color: var(--dark);">{{ $prod->name }}</td>
                                <td style="padding: 12px; font-weight: 700; color: var(--royal);">
                                    TZS {{ number_format($prod->price, 0) }}
                                </td>
                                <td style="padding: 12px; color: #666; font-size: 13px;">
                                    {{ $prod->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #888; padding: 25px;">
                                    No products found in store catalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
