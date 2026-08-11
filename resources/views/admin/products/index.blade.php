@extends('layouts.admin')

@section('title', 'Product Catalog')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Product Catalog</h1>
            <p style="color: #666; font-size: 14px;">Manage items currently listed for sale in your storefront.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn-action btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Products Section -->
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>All Products</h2>
            </div>
            <div class="table-responsive" style="padding: 10px;">
                <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category / Badge</th>
                            <th>Price</th>
                            <th>Stock Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td>
                                    <img src="{{ $product->image_url }}" alt="" class="product-img-th">
                                </td>
                                <td style="font-weight: 600; color: var(--dark);">{{ $product->name }}</td>
                                <td>
                                    @if($product->badge)
                                        <span class="badge-table badge-blue">{{ $product->badge }}</span>
                                    @else
                                        <span style="color: #bbb;">None</span>
                                    @endif
                                </td>
                                <td style="font-weight: 700;">
                                    @if($product->is_from_price)
                                        <span style="font-weight: 400; font-size: 12px; color: #777;">From</span>
                                    @endif
                                    TZS {{ number_format($product->price, 0) }}
                                </td>
                                <td>
                                    @if($product->in_stock)
                                        <span class="badge-table" style="background: rgba(40, 167, 69, 0.1); color: #28a745; padding: 5px 10px; border-radius: 12px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;"><i class="fas fa-check-circle"></i> In Stock</span>
                                    @else
                                        <span class="badge-table" style="background: rgba(220, 53, 69, 0.1); color: #dc3545; padding: 5px 10px; border-radius: 12px; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;"><i class="fas fa-times-circle"></i> Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action btn-sm btn-edit" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: #333;"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" style="margin: 0; display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-sm btn-danger" style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(220, 53, 69, 0.1); color: #dc3545; cursor: pointer;">
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
