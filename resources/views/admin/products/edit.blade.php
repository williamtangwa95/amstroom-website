@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.dashboard') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Edit Product: {{ $product->name }}</h1>
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

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Dell Latitude 3310" value="{{ old('name', $product->name) }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="category_id">Product Category (Optional)</label>
                    <select name="category_id" id="category_id" class="form-control" style="background: #f8fafc; border: 1px solid #cbd5e1; height: auto;">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price (TZS) *</label>
                    <input type="number" name="price" id="price" class="form-control" placeholder="e.g. 650000" value="{{ old('price', (int)$product->price) }}" required>
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" name="is_from_price" id="is_from_price" {{ old('is_from_price', $product->is_from_price) ? 'checked' : '' }}>
                    <label for="is_from_price">This is a starting price (Prefixes display with "From TZS ...")</label>
                </div>

                <div class="form-group checkbox-group" style="margin-top: 10px;">
                    <input type="checkbox" name="in_stock" id="in_stock" {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}>
                    <label for="in_stock" style="font-weight: 600; color: #28a745;"><i class="fas fa-boxes" style="margin-right: 5px;"></i> Product is In Stock</label>
                </div>

                <div class="form-group">
                    <label>Current Product Image</label>
                    <div style="margin-bottom: 15px;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-width: 200px; height: auto; border-radius: 8px; border: 1px solid #ddd; display: block; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_file">Upload New Product Image</label>
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*" style="padding: 8px 12px;">
                    <small style="color: #777; font-size: 12px; margin-top: 5px; display: block;">
                        Choose an image file from your computer (JPEG, PNG, JPG, GIF, WEBP - max 2MB). Leave empty to keep current image.
                    </small>
                </div>

                <div style="text-align: center; margin: 15px 0; color: #888; font-weight: 500; font-size: 13px;">— OR —</div>

                <div class="form-group">
                    <label for="image_url">Product Image URL</label>
                    <input type="url" name="image_url" id="image_url" class="form-control" placeholder="https://images.unsplash.com/photo-..." value="{{ old('image_url', $product->image_url) }}">
                    <small style="color: #777; font-size: 12px; margin-top: 5px; display: block;">
                        Alternatively, update the absolute link to the image.
                    </small>
                </div>

                <div class="form-group">
                    <label for="description">Specifications / Description *</label>
                    <textarea name="description" id="description" rows="6" class="form-control" placeholder="Enter specifications line by line" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Update Product</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-action btn-back">Cancel</a>
                </div>

            </form>
        </div>
    </div>
@endsection
