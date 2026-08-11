@extends('layouts.admin')

@section('title', 'Add Stat Counter')

@section('content')
    <div class="admin-header" style="justify-content: flex-start; gap: 20px;">
        <a href="{{ route('admin.homepage.index') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Add New Stat Counter</h1>
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

            <form action="{{ route('admin.stats.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="value">Metric Value *</label>
                    <input type="text" name="value" id="value" class="form-control" placeholder="e.g. 500+ or 30+ Days" value="{{ old('value') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="label">Label Name *</label>
                    <input type="text" name="label" id="label" class="form-control" placeholder="e.g. Happy Customers or Warranty" value="{{ old('label') }}" required>
                </div>

                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" placeholder="e.g. 0" value="{{ old('sort_order', 0) }}">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action btn-primary"><i class="fas fa-save"></i> Save Stat</button>
                    <a href="{{ route('admin.homepage.index') }}" class="btn-action btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
