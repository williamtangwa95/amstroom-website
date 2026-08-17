@extends('layouts.admin')

@section('title', 'Add Payment Method')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.payment-methods.index') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Add New Payment Method</h1>
    </div>

    <div class="admin-form">
        <div class="form-card" style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 30px; border: 1px solid #eef2f6; max-width: 600px;">
            @if($errors->any())
                <div class="error-box" style="background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 15px; border-radius: 6px; margin-bottom: 20px; color: #dc3545;">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="name" style="font-weight: 600; display: block; margin-bottom: 8px; color: var(--dark);">Payment Method Name *</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. CRDB Bank, Lipa kwa M-Pesa, Airtel Money" value="{{ old('name') }}" required autofocus style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14.5px; box-sizing: border-box;">
                    <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                        The branding name shown to customers during checkout.
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="account_number" style="font-weight: 600; display: block; margin-bottom: 8px; color: var(--dark);">Account or Lipa Namba *</label>
                    <input type="text" name="account_number" id="account_number" class="form-control" placeholder="e.g. 0152431782390 or LIPA NUMBER: 522123" value="{{ old('account_number') }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14.5px; box-sizing: border-box; font-family: monospace;">
                    <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                        The account or Lipa code they need to send funds to.
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="account_name" style="font-weight: 600; display: block; margin-bottom: 8px; color: var(--dark);">Account Name *</label>
                    <input type="text" name="account_name" id="account_name" class="form-control" placeholder="e.g. AMSTROOM COMPUTERS LTD" value="{{ old('account_name') }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14.5px; box-sizing: border-box;">
                    <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                        The registered name of the account holder or organization.
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="logo" style="font-weight: 600; display: block; margin-bottom: 8px; color: var(--dark);">Account Logo (Optional)</label>
                    <input type="file" name="logo" id="logo" class="form-control" accept="image/*" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
                    <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                        Upload a small icon representing the payment provider (PNG/JPG/SVG/WebP).
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 25px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="is_active" style="font-weight: 600; cursor: pointer; color: var(--dark); margin: 0; user-select: none;">Mark as Active</label>
                </div>

                <div class="form-actions" style="display: flex; gap: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 10px;">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Save Method</button>
                    <a href="{{ route('admin.payment-methods.index') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
