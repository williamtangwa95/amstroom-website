@extends('layouts.admin')

@section('title', 'Hero Sliders')

@section('content')
    <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Hero Slides</h1>
        <a href="{{ route('admin.sliders.create') }}" class="btn-action btn-primary"><i class="fas fa-plus"></i> Add New Slide</a>
    </div>

    <div class="table-card" style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
        <div class="table-responsive">
            <table class="datatable table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Order</th>
                        <th style="width: 120px;">Image</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th style="width: 150px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $slider)
                        <tr>
                            <td>
                                <span class="badge-table badge-gold" style="font-family: monospace; font-size: 13px;">#{{ $slider->sort_order }}</span>
                            </td>
                            <td>
                                @if($slider->image_path)
                                    <img src="{{ asset($slider->image_path) }}" alt="Slide Image" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                @else
                                    <div style="width: 80px; height: 50px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #64748b; font-weight: 600;">Default</div>
                                @endif
                            </td>
                            <td>
                                <strong style="font-size: 15px; color: var(--dark);">{{ $slider->title }}</strong>
                                @if($slider->description)
                                    <p style="font-size: 12px; color: #64748b; margin: 3px 0 0 0; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 400px;">{{ $slider->description }}</p>
                                @endif
                            </td>
                            <td>
                                @if($slider->status)
                                    <span class="badge-table badge-blue" style="background: rgba(40,167,69,0.1); color: var(--success);">Active</span>
                                @else
                                    <span class="badge-table" style="background: rgba(108,117,125,0.1); color: #6c757d;">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn-action" style="padding: 6px 12px; background: rgba(11,79,181,0.1); color: var(--primary); border: none; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600;"><i class="fas fa-edit"></i> Edit</a>
                                    
                                    <form action="{{ route('admin.sliders.delete', $slider->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slide?');" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-danger" style="padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
