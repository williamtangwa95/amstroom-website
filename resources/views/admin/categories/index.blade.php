@extends('layouts.admin')

@section('title', 'Product Categories')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Product Categories</h1>
            <p style="color: #666; font-size: 14px;">Register and manage grouping categories for your product catalog.</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.create') }}" class="btn-action btn-primary">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Category List</h2>
            </div>
            <div class="table-responsive" style="padding: 10px;">
                <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th>Category Name</th>
                            <th>Slug (URL Identifier)</th>
                            <th style="text-align: center;">Products Count</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="font-weight: 600; color: var(--dark);">
                                    {{ $category->name }}
                                </td>
                                <td style="color: #555;">{{ $category->slug }}</td>
                                <td style="text-align: center;">
                                    <span class="badge-table badge-blue">
                                        {{ $category->products_count }}
                                    </span>
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-action btn-sm btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Are you sure you want to delete this category? Any associated products will have their category cleared.');">
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
