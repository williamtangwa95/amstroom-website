@extends('layouts.admin')

@section('title', 'Product Requests & Orders')

@section('content')
    <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1>Product Requests & Orders</h1>
            <p style="color: #666; font-size: 14px;">Manage custom product requests and shopping cart checkouts submitted by website visitors.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.requests.index') }}" class="btn-action" style="padding: 10px 15px; background: {{ !request('type') ? 'var(--primary)' : 'white' }}; color: {{ !request('type') ? 'white' : 'var(--dark)' }}; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 13.5px; border: 1px solid #ddd; transition: 0.3s;">All</a>
            <a href="{{ route('admin.requests.index', ['type' => 'cart']) }}" class="btn-action" style="padding: 10px 15px; background: {{ request('type') === 'cart' ? 'var(--primary)' : 'white' }}; color: {{ request('type') === 'cart' ? 'white' : 'var(--dark)' }}; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 13.5px; border: 1px solid #ddd; transition: 0.3s;"><i class="fas fa-shopping-cart" style="margin-right: 5px;"></i> Cart Orders</a>
            <a href="{{ route('admin.requests.index', ['type' => 'custom']) }}" class="btn-action" style="padding: 10px 15px; background: {{ request('type') === 'custom' ? 'var(--primary)' : 'white' }}; color: {{ request('type') === 'custom' ? 'white' : 'var(--dark)' }}; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 13.5px; border: 1px solid #ddd; transition: 0.3s;"><i class="fas fa-magic" style="margin-right: 5px;"></i> Custom Requests</a>
        </div>
    </div>

    <!-- Product Requests Section -->
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="dashboard-card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eef2f6; padding: 20px;">
                <h2>List of Requests ({{ count($requests) }})</h2>
                @if(request('status'))
                    <a href="{{ route('admin.requests.index', request()->except('status')) }}" style="font-size: 13px; color: var(--primary); text-decoration: none; font-weight: 600;">Clear Status Filter</a>
                @endif
            </div>

            <div class="table-responsive" style="padding: 10px;">
                <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th style="padding: 12px;">Customer Details</th>
                            <th style="padding: 12px;">Request Type</th>
                            <th style="padding: 12px; min-width: 300px;">Items / Specifications</th>
                            <th style="padding: 12px;">Total Price</th>
                            <th style="padding: 12px; width: 140px;">Status</th>
                            <th style="padding: 12px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <!-- Customer Details -->
                                <td style="padding: 12px; vertical-align: top;">
                                    <div style="font-weight: 600; color: var(--dark); font-size: 14.5px;">{{ $req->name }}</div>
                                    <div style="margin-top: 5px; font-size: 13px;">
                                        <i class="fas fa-phone" style="color: #777; width: 15px;"></i> 
                                        <a href="tel:{{ $req->phone }}">{{ $req->phone }}</a>
                                    </div>
                                    @if($req->email)
                                        <div style="margin-top: 3px; font-size: 13px;">
                                            <i class="fas fa-envelope" style="color: #777; width: 15px;"></i> 
                                            <a href="mailto:{{ $req->email }}">{{ $req->email }}</a>
                                        </div>
                                    @endif
                                    <div style="margin-top: 5px; font-size: 12px; color: #888;">
                                        {{ $req->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </td>

                                <!-- Request Type Badge -->
                                <td style="padding: 12px; vertical-align: top;">
                                    @if($req->request_type === 'cart')
                                        <span style="background: rgba(40, 167, 69, 0.12); color: #28a745; padding: 5px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-shopping-cart" style="font-size: 10px;"></i> Cart Order
                                        </span>
                                    @else
                                        <span style="background: rgba(11, 79, 181, 0.12); color: #0B4FB5; padding: 5px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fas fa-magic" style="font-size: 10px;"></i> Custom
                                        </span>
                                    @endif
                                </td>

                                <!-- Items / Details -->
                                <td style="padding: 12px; vertical-align: top; max-width: 450px;">
                                    @if($req->request_type === 'cart')
                                        @php
                                            $cartItems = $req->cart_items;
                                        @endphp
                                        @if(is_array($cartItems) && count($cartItems) > 0)
                                            <div style="background: #f8fafc; border: 1px solid #eef2f6; border-radius: 8px; padding: 10px;">
                                                <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 6px;">
                                                    @foreach($cartItems as $item)
                                                        <li style="font-size: 13.5px; display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 4px; gap: 10px;">
                                                            <span>
                                                                <strong style="color: var(--dark);">{{ $item['quantity'] }}x</strong> {{ $item['name'] }}
                                                            </span>
                                                            <span style="color: #666; font-weight: 600; flex-shrink: 0;">
                                                                TZS {{ number_format($item['price'] * $item['quantity'], 0) }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div style="color: #e11d48; font-style: italic; font-size: 13px;">Empty cart details</div>
                                        @endif
                                    @else
                                        <div style="font-size: 14px; line-height: 1.6; white-space: pre-line; color: #333; background: #fffbf0; border: 1px solid #ffeeba; border-radius: 8px; padding: 10px;">{!! e($req->details) !!}</div>
                                    @endif
                                </td>

                                <!-- Total Price -->
                                <td style="padding: 12px; vertical-align: top; font-weight: 700; font-size: 15px; color: var(--royal);">
                                    @if($req->request_type === 'cart')
                                        TZS {{ number_format($req->total_price, 0) }}
                                    @else
                                        <span style="color: #888; font-weight: normal; font-size: 13px;">N/A (Custom)</span>
                                    @endif
                                </td>

                                <!-- Status Dropdown -->
                                <td style="padding: 12px; vertical-align: top;">
                                    <form action="{{ route('admin.requests.updateStatus', $req->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @php
                                            $colorMap = [
                                                'pending' => ['bg' => '#ffc107', 'text' => '#000'],
                                                'in_progress' => ['bg' => '#17a2b8', 'text' => '#fff'],
                                                'completed' => ['bg' => '#28a745', 'text' => '#fff'],
                                                'cancelled' => ['bg' => '#dc3545', 'text' => '#fff'],
                                            ];
                                            $currentStyle = $colorMap[$req->status] ?? ['bg' => '#6c757d', 'text' => '#fff'];
                                        @endphp
                                        <select name="status" onchange="this.form.submit()" style="padding: 6px 10px; border-radius: 8px; font-weight: 600; font-size: 12.5px; cursor: pointer; outline: none; border: 1px solid #ddd; width: 100%; text-align: center; background-color: {{ $currentStyle['bg'] }}; color: {{ $currentStyle['text'] }}; transition: 0.2s;">
                                            <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Pending</option>
                                            <option value="in_progress" {{ $req->status === 'in_progress' ? 'selected' : '' }} style="background-color: #fff; color: #000;">In Progress</option>
                                            <option value="completed" {{ $req->status === 'completed' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Completed</option>
                                            <option value="cancelled" {{ $req->status === 'cancelled' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Cancelled</option>
                                        </select>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td style="padding: 12px; vertical-align: top; text-align: center;">
                                    <form action="{{ route('admin.requests.delete', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?')" style="margin: 0; display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-logout-sidebar" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 16px; padding: 5px 10px; transition: 0.2s;" title="Delete Request" onmouseover="this.style.color='#900'" onmouseout="this.style.color='#dc3545'">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #888; padding: 40px; font-size: 15px;">
                                    <i class="fas fa-box-open" style="font-size: 40px; margin-bottom: 12px; display: block; color: var(--primary);"></i>
                                    No requests or orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
