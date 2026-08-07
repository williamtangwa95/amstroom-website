@extends('layouts.admin')

@section('title', 'Create Slide')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.sliders.index') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Create Hero Slide</h1>
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

            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="title">Slide Title *</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. FAST & RELIABLE TECHNOLOGY SOLUTIONS">
                </div>

                <div class="form-group">
                    <label for="description">Slide Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Slide content text description..." style="resize: vertical;">{{ old('description') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-top: 1px solid #f1f5f9; padding-top: 20px; margin-top: 20px;">
                    <div class="form-group">
                        <label for="primary_btn_text">Primary Button Text</label>
                        <input type="text" name="primary_btn_text" id="primary_btn_text" class="form-control" value="{{ old('primary_btn_text') }}" placeholder="e.g. Browse Products">
                    </div>
                    <div class="form-group">
                        <label for="primary_btn_url">Primary Button URL</label>
                        <input type="text" name="primary_btn_url" id="primary_btn_url" class="form-control" value="{{ old('primary_btn_url') }}" placeholder="e.g. #products">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="secondary_btn_text">Secondary Button Text</label>
                        <input type="text" name="secondary_btn_text" id="secondary_btn_text" class="form-control" value="{{ old('secondary_btn_text') }}" placeholder="e.g. WhatsApp Us">
                    </div>
                    <div class="form-group">
                        <label for="secondary_btn_url">Secondary Button URL</label>
                        <input type="text" name="secondary_btn_url" id="secondary_btn_url" class="form-control" value="{{ old('secondary_btn_url') }}" placeholder="e.g. https://wa.me/...">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_file">Upload Background Image</label>
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*" style="padding: 8px 12px;">
                    <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block;">
                        Leave blank to fall back to the premium default tech background image.
                    </small>
                    
                    <!-- Preview -->
                    <div style="margin-top: 15px; display: none;" id="preview-wrapper">
                        <img id="image-preview" src="#" alt="Preview" style="max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 8px; border: 1px dashed #cbd5e1; padding: 5px; background: #fff;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Enabled / Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Disabled / Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sort_order">Sort Order *</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', '0') }}" required>
                        <small style="color: #64748b; font-size: 11px; margin-top: 3px; display: block;">
                            Slides are ordered sequentially starting from lowest order value.
                        </small>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Create Slide</button>
                    <a href="{{ route('admin.sliders.index') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('image_file').addEventListener('change', function(e) {
            const reader = new FileReader();
            const wrapper = document.getElementById('preview-wrapper');
            const preview = document.getElementById('image-preview');
            
            reader.onload = function(event) {
                preview.src = event.target.result;
                wrapper.style.display = 'block';
            };
            
            if(e.target.files[0]) {
                reader.readAsDataURL(e.target.files[0]);
            } else {
                wrapper.style.display = 'none';
            }
        });
    </script>
@endsection
