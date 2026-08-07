@extends('layouts.admin')

@section('title', 'Register New User')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.users.index') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Register New User</h1>
    </div>

    <div class="admin-form">
        <div class="form-card">
            @if($errors->any())
                <div class="error-box" style="background: rgba(220, 53, 69, 0.1); border-left: 4px solid var(--danger); padding: 15px; border-radius: 6px; margin-bottom: 20px; color: var(--danger);">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. William Tangwa" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="e.g. user@amstroom.com" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="role">User Role *</label>
                    <select name="role" id="role" class="form-control" style="background: #f8fafc; border: 1px solid #cbd5e1; height: auto;" required>
                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager (Can manage products & view inquiries)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Full control, can manage other users)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-type password" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-user-plus"></i> Register User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
