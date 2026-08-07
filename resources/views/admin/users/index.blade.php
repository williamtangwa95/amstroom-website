@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
    <div class="admin-header">
        <div>
            <h1>User Accounts</h1>
            <p style="color: #666; font-size: 14px;">Manage administrative and oversight access accounts for the website.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn-action btn-primary">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        </div>
    </div>

    <div class="dashboard-card" style="margin-top: 30px;">
        <div class="card-header">
            <h2>Registered Users</h2>
        </div>
        <div style="overflow-x: auto; padding: 10px;">
            <table class="table datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eef2f6;">
                        <th style="padding: 12px;">Name</th>
                        <th style="padding: 12px;">Email Address</th>
                        <th style="padding: 12px;">Role</th>
                        <th style="padding: 12px;">Created Date</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="border-bottom: 1px solid #eef2f6; hover { background: #f8fafc; }">
                            <td style="padding: 12px; font-weight: 600; color: var(--dark);">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span style="background: rgba(11, 79, 181, 0.1); color: var(--primary); padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 5px; font-weight: 500;">You</span>
                                @endif
                            </td>
                            <td style="padding: 12px;">{{ $user->email }}</td>
                            <td style="padding: 12px;">
                                @if($user->role === 'admin')
                                    <span style="background: rgba(40, 167, 69, 0.1); color: #28a745; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">ADMIN</span>
                                @else
                                    <span style="background: rgba(108, 117, 125, 0.1); color: #6c757d; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">MANAGER</span>
                                @endif
                            </td>
                            <td style="padding: 12px; color: #666; font-size: 13px;">
                                {{ $user->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td style="padding: 12px; text-align: center; white-space: nowrap;">
                                @if($user->id !== auth()->id())
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: #333; margin-right: 5px;"><i class="fas fa-edit"></i> Edit</a>
                                    
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        <button type="submit" class="btn-action btn-delete" style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(220, 53, 69, 0.1); color: #dc3545; cursor: pointer;"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.profile.edit') }}" class="btn-action" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: var(--primary);"><i class="fas fa-user-circle"></i> Profile Settings</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
