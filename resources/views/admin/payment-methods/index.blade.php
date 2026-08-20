@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Payment Methods</h1>
            <p style="color: #666; font-size: 14px;">Register and manage various payment accounts for customer checkout.</p>
        </div>
        <div>
            <a href="{{ route('admin.payment-methods.create') }}" class="btn-action btn-primary">
                <i class="fas fa-plus"></i> Add Payment Method
            </a>
        </div>
    </div>

    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Registered Methods</h2>
            </div>
            <div class="table-responsive" style="padding: 10px;">
                <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th style="width: 80px;">Logo</th>
                            <th>Method Name</th>
                            <th>Account / Lipa Number</th>
                            <th>Status</th>
                            <th style="text-align: center; width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethods as $method)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td>
                                    @if($method->logo_path)
                                        <div class="zoomable-image-container" style="width: 48px; height: 48px; background: #f8fafc; padding: 4px;" data-zoom-caption="{{ $method->name }}">
                                            <img src="{{ asset($method->logo_path) }}" alt="{{ $method->name }}" style="object-fit: contain;">
                                            <div class="zoom-overlay">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: #e2e8f0; border-radius: 8px; color: #64748b; font-size: 18px;">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                    @endif
                                </td>
                                <td style="font-weight: 600; color: var(--dark);">
                                    {{ $method->name }}
                                </td>
                                <td style="font-family: monospace; font-size: 14px; font-weight: bold; color: #0B4FB5; letter-spacing: 0.5px;">
                                    {{ $method->account_number }}
                                    @if($method->account_name)
                                        <div style="font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: normal; color: #64748b; margin-top: 3px;">
                                            Name: <span style="font-weight: 600; color: #334155;">{{ $method->account_name }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($method->is_active)
                                        <span class="badge-table badge-green" style="background: rgba(40, 167, 69, 0.1); color: #28a745; padding: 5px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge-table badge-danger" style="background: rgba(220, 53, 69, 0.1); color: #dc3545; padding: 5px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-times-circle"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="btn-action btn-sm btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.payment-methods.delete', $method->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Are you sure you want to delete this payment method?');">
                                            @csrf
                                            <button type="submit" class="btn-action btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
