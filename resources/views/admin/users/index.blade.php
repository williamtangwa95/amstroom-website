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

    <!-- Users Section -->
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Registered Users</h2>
            </div>
            <div class="table-responsive" style="padding: 10px;">
                <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th>Created Date</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="font-weight: 600; color: var(--dark);">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge-table badge-blue" style="margin-left: 5px;">You</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge-table badge-blue">ADMIN</span>
                                    @else
                                        <span class="badge-table badge-gold">MANAGER</span>
                                    @endif
                                </td>
                                <td style="color: #666; font-size: 13px;">
                                    {{ $user->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        @if($user->id !== auth()->id())
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-sm btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                <button type="submit" class="btn-action btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.profile.edit') }}" class="btn-action btn-sm btn-primary">
                                                <i class="fas fa-user-circle"></i> Profile Settings
                                            </a>
                                        @endif
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
