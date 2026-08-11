@extends('layouts.admin')

@section('title', 'Add Why Choose Us Feature')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.homepage.index') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Add Homepage Feature</h1>
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

            <form action="{{ route('admin.why-chooses.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="title">Feature Title *</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Quality Guaranteed" value="{{ old('title') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="icon">Font Awesome Icon Class *</label>
                    <input type="text" name="icon" id="icon" class="form-control" placeholder="e.g. fas fa-shield-halved" value="{{ old('icon') }}" required>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Use FontAwesome class names. Examples: <code style="background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">fas fa-shield-halved</code>, <code style="background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">fas fa-tags</code>, <code style="background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">fas fa-headset</code>.
                    </small>
                </div>

                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" placeholder="e.g. 0" value="{{ old('sort_order', 0) }}">
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="Describe the feature details..." required>{{ old('description') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Save Feature</button>
                    <a href="{{ route('admin.homepage.index') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
