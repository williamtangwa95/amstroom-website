@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.dashboard') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h1>My Profile Settings</h1>
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

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Account Name *</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="role">Account Role</label>
                    <input type="text" id="role" class="form-control" value="{{ strtoupper($user->role) }}" readonly style="background: #eef2f6; cursor: not-allowed; color: #555;">
                    <small style="color: #777; font-size: 12px; margin-top: 5px; display: block;">
                        Your administrative role is managed by the system administrator.
                    </small>
                </div>

                <div style="border-top: 1px dashed #cbd5e1; margin: 30px 0; padding-top: 20px;">
                    <h3 style="margin-bottom: 15px; font-size: 16px; color: var(--primary);">Change Password (Optional)</h3>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave empty to keep current password" style="padding-right: 45px;">
                        <button type="button" class="toggle-password" data-target="password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 16px; outline: none; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-type new password" style="padding-right: 45px;">
                        <button type="button" class="toggle-password" data-target="password_confirmation" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 16px; outline: none; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.toggle-password').on('click', function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const icon = $(this).find('i');
                
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endsection
