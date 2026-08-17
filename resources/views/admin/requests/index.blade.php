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
                    @foreach($requests as $req)
                    <tr style="border-bottom: 1px solid #eef2f6;">
                        <!-- Customer Details -->
                        <td style="padding: 12px; vertical-align: top;">
                            <div style="font-weight: 700; color: var(--dark); font-size: 15px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user" style="color: var(--primary); font-size: 13px;"></i> {{ $req->name }}
                            </div>

                            <div style="margin-top: 8px; display: flex; flex-direction: column; gap: 6px;">
                                <div style="font-size: 13px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <span style="display: inline-flex; align-items: center; gap: 4px; color: #555;">
                                        <i class="fas fa-phone-alt" style="color: #64748b; width: 14px;"></i>
                                        <a href="tel:{{ $req->phone }}" style="font-weight: 600; color: var(--dark); text-decoration: none;">{{ $req->phone }}</a>
                                    </span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $req->phone) }}" target="_blank" style="background: rgba(40, 167, 69, 0.1); color: #28a745; padding: 2px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; border: 1px solid rgba(40, 167, 69, 0.2); transition: 0.2s;" onmouseover="this.style.background='rgba(40, 167, 69, 0.2)'" onmouseout="this.style.background='rgba(40, 167, 69, 0.1)'">
                                        <i class="fab fa-whatsapp"></i> Chat WhatsApp
                                    </a>
                                </div>
                                @if($req->email)
                                <div style="font-size: 13px; display: inline-flex; align-items: center; gap: 4px; color: #555;">
                                    <i class="fas fa-envelope" style="color: #64748b; width: 14px;"></i>
                                    <a href="mailto:{{ $req->email }}" style="color: #555; text-decoration: none;">{{ $req->email }}</a>
                                </div>
                                @endif
                            </div>
                            <div style="margin-top: 10px; font-size: 11px; color: #888; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="far fa-clock" style="font-size: 10.5px;"></i> {{ $req->created_at->format('M d, Y h:i A') }} ({{ $req->created_at->diffForHumans() }})
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

                            @if($req->paymentMethod)
                            <div style="margin-top: 10px; padding: 8px 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; font-size: 12.5px; color: #166534; display: flex; flex-direction: column; gap: 4px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($req->paymentMethod->logo_path)
                                        <img src="{{ asset($req->paymentMethod->logo_path) }}" alt="{{ $req->paymentMethod->name }}" style="height: 20px; width: 20px; object-fit: contain; border-radius: 3px; background: white; border: 1px solid #e2e8f0; padding: 1px;">
                                    @else
                                        <i class="fas fa-credit-card"></i>
                                    @endif
                                    <span>
                                        <strong>Paid via:</strong> {{ $req->paymentMethod->name }}
                                    </span>
                                </div>
                                @if($req->paymentMethod->account_name)
                                <div style="font-size: 12px; color: #1e7e34; margin-left: 28px;">
                                    <strong>Name:</strong> {{ $req->paymentMethod->account_name }}
                                </div>
                                @endif
                                <div style="font-size: 12px; color: #1e7e34; margin-left: 28px;">
                                    <strong>Number:</strong> {{ $req->paymentMethod->account_number }}
                                </div>
                            </div>
                            @endif
                            
                            @if($req->reference_number)
                            <div style="margin-top: 5px; padding: 8px 12px; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; font-size: 12.5px; color: #0369a1; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-receipt"></i>
                                <span>
                                    <strong>Ref Number:</strong> <code style="font-family: monospace; font-size: 13px; font-weight: 700; background: #e0f2fe; padding: 2px 5px; border-radius: 4px;">{{ $req->reference_number }}</code>
                                </span>
                            </div>
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
                                'paid' => ['bg' => '#28a745', 'text' => '#fff'],
                                'unpaid' => ['bg' => '#dc3545', 'text' => '#fff'],
                                ];
                                $currentStyle = $colorMap[$req->status] ?? ['bg' => '#6c757d', 'text' => '#fff'];
                                @endphp
                                <select name="status" onchange="this.form.submit()" style="padding: 6px 10px; border-radius: 8px; font-weight: 600; font-size: 12.5px; cursor: pointer; outline: none; border: 1px solid #ddd; width: 100%; text-align: center; background-color: {{ $currentStyle['bg'] }}; color: {{ $currentStyle['text'] }}; transition: 0.2s;">
                                    <option value="pending" {{ $req->status === 'pending' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Pending</option>
                                    <option value="in_progress" {{ $req->status === 'in_progress' ? 'selected' : '' }} style="background-color: #fff; color: #000;">In Progress</option>
                                    <option value="completed" {{ $req->status === 'completed' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Completed</option>
                                    <option value="cancelled" {{ $req->status === 'cancelled' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Cancelled</option>
                                    <option value="paid" {{ $req->status === 'paid' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Paid</option>
                                    <option value="unpaid" {{ $req->status === 'unpaid' ? 'selected' : '' }} style="background-color: #fff; color: #000;">Unpaid</option>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection