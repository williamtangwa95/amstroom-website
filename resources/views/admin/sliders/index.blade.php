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
                        <th style="width: 250px; text-align: center;">Actions</th>
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
                                    <div class="zoomable-image-container" style="width: 80px; height: 50px;" data-zoom-caption="{{ $slider->title }}">
                                        <img src="{{ asset($slider->image_path) }}" alt="{{ $slider->title }}">
                                        <div class="zoom-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
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
                                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    <button type="button" class="btn-action btn-sm toggle-details" 
                                        data-id="{{ $slider->id }}" 
                                        data-title="{{ $slider->title }}" 
                                        data-description="{{ $slider->description }}"
                                        data-primary-btn-text="{{ $slider->primary_btn_text }}"
                                        data-primary-btn-url="{{ $slider->primary_btn_url }}"
                                        data-secondary-btn-text="{{ $slider->secondary_btn_text }}"
                                        data-secondary-btn-url="{{ $slider->secondary_btn_url }}"
                                        data-overlay-opacity="{{ $slider->overlay_opacity }}"
                                        style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(11, 79, 181, 0.1); color: var(--primary); cursor: pointer; font-weight: 600;">
                                        <i class="fas fa-chevron-down"></i> Details
                                    </button>
                                    
                                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn-action btn-sm btn-edit" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: #333;"><i class="fas fa-edit"></i> Edit</a>
                                    
                                    <form action="{{ route('admin.sliders.delete', $slider->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slide?');" style="margin: 0; display: inline;">
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function escapeHtml(string) {
                if (string === null || string === undefined) return '';
                return String(string).replace(/[&<>"']/g, function (s) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    }[s];
                });
            }

            // Close all open detail rows when the table redraws (pagination, sort, search)
            $('.datatable').on('draw.dt', function() {
                $('.slider-details-row').remove();
                $('.toggle-details').html('<i class="fas fa-chevron-down"></i> Details').removeClass('active');
            });

            // Toggle slider detail row
            $(document).on('click', '.toggle-details', function(e) {
                e.preventDefault();
                const btn = $(this);
                const sliderId = btn.data('id');
                const title = btn.data('title');
                const description = btn.data('description');
                const primaryBtnText = btn.data('primary-btn-text');
                const primaryBtnUrl = btn.data('primary-btn-url');
                const secondaryBtnText = btn.data('secondary-btn-text');
                const secondaryBtnUrl = btn.data('secondary-btn-url');
                const overlayOpacity = btn.data('overlay-opacity');
                const row = btn.closest('tr');
                
                let detailsRow = $('#details-row-' + sliderId);
                
                if (detailsRow.length) {
                    detailsRow.find('.details-content-wrapper').slideUp(200, function() {
                        detailsRow.remove();
                    });
                    btn.html('<i class="fas fa-chevron-down"></i> Details');
                    btn.removeClass('active');
                } else {
                    const newRowHtml = `
                        <tr id="details-row-${sliderId}" class="slider-details-row" style="background: #f8fafc; border-bottom: 1px solid #eef2f6;">
                            <td colspan="5" style="padding: 0;">
                                <div class="details-content-wrapper" style="display: none; padding: 20px 25px;">
                                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); border-left: 4px solid var(--royal); display: flex; flex-direction: column; gap: 15px;">
                                        <h4 style="margin: 0; color: var(--dark); font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-info-circle" style="color: var(--royal);"></i> Details for Slide: ${escapeHtml(title)}
                                        </h4>
                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                            <div>
                                                <h5 style="margin: 0 0 6px 0; color: #475569; font-size: 12px; font-weight: 700; text-transform: uppercase;">Description</h5>
                                                <p style="margin: 0; color: #64748b; font-size: 13.5px; line-height: 1.5; white-space: pre-line;">${escapeHtml(description) || '<span style="color: #94a3b8; font-style: italic;">No description</span>'}</p>
                                            </div>
                                            <div>
                                                <h5 style="margin: 0 0 6px 0; color: #475569; font-size: 12px; font-weight: 700; text-transform: uppercase;">Buttons & Settings</h5>
                                                <ul style="margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 6px; font-size: 13.5px; color: #64748b;">
                                                    <li><strong>Primary Button Text:</strong> ${escapeHtml(primaryBtnText) || '<span style="color: #94a3b8; font-style: italic;">None</span>'}</li>
                                                    <li><strong>Primary Button URL:</strong> ${primaryBtnUrl ? `<a href="${escapeHtml(primaryBtnUrl)}" target="_blank" style="color: var(--primary); text-decoration: none;">${escapeHtml(primaryBtnUrl)}</a>` : '<span style="color: #94a3b8; font-style: italic;">None</span>'}</li>
                                                    <li><strong>Secondary Button Text:</strong> ${escapeHtml(secondaryBtnText) || '<span style="color: #94a3b8; font-style: italic;">None</span>'}</li>
                                                    <li><strong>Secondary Button URL:</strong> ${secondaryBtnUrl ? `<a href="${escapeHtml(secondaryBtnUrl)}" target="_blank" style="color: var(--primary); text-decoration: none;">${escapeHtml(secondaryBtnUrl)}</a>` : '<span style="color: #94a3b8; font-style: italic;">None</span>'}</li>
                                                    <li><strong>Overlay Opacity:</strong> ${escapeHtml(overlayOpacity)}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    row.after(newRowHtml);
                    $('#details-row-' + sliderId).find('.details-content-wrapper').slideDown(250);
                    btn.html('<i class="fas fa-chevron-up"></i> Close');
                    btn.addClass('active');
                }
            });
        });
    </script>
@endsection
