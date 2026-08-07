@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.categories.index') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Edit Category: {{ $category->name }}</h1>
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

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Category Name *</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required autofocus>
                    <small style="color: #777; font-size: 12px; margin-top: 5px; display: block;">
                        Updating the name will update the slug and badge text on all associated products.
                    </small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Update Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
