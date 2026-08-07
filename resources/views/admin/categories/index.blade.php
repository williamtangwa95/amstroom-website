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

    <div class="dashboard-card" style="margin-top: 30px;">
        <div class="card-header">
            <h2>Category List</h2>
        </div>
        <div style="overflow-x: auto; padding: 10px;">
            <table class="table datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eef2f6;">
                        <th style="padding: 12px;">Category Name</th>
                        <th style="padding: 12px;">Slug (URL Identifier)</th>
                        <th style="padding: 12px; text-align: center;">Products Count</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr style="border-bottom: 1px solid #eef2f6;">
                            <td style="padding: 12px; font-weight: 600; color: var(--dark);">
                                {{ $category->name }}
                            </td>
                            <td style="padding: 12px; color: #555;">{{ $category->slug }}</td>
                            <td style="padding: 12px; text-align: center;">
                                <span style="background: rgba(11, 79, 181, 0.1); color: var(--primary); padding: 4px 12px; border-radius: 12px; font-size: 12.5px; font-weight: 600;">
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center; white-space: nowrap;">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-action" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: #333; margin-right: 5px;"><i class="fas fa-edit"></i> Edit</a>
                                
                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category? Any associated products will have their category cleared.');">
                                    @csrf
                                    <button type="submit" class="btn-action" style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(220, 53, 69, 0.1); color: #dc3545; cursor: pointer;"><i class="fas fa-trash-alt"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
