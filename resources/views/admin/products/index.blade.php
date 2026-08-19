@extends('layouts.admin')

@section('title', 'Product Catalog')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Product Catalog</h1>
            <p style="color: #666; font-size: 14px;">Manage items currently listed for sale in your storefront.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <button type="button" id="export-excel-btn" class="btn-action" style="background: #217346; color: white; border: none; cursor: pointer; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; transition: all 0.2s;" onmouseover="this.style.background='#1a5c38'" onmouseout="this.style.background='#217346'">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn-action btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Products Section -->
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="dashboard-card">
            
            <form id="reorder-form" action="{{ route('admin.products.reorder') }}" method="POST" style="margin: 0;">
                @csrf
                
                <div class="card-header" style="border-bottom: 1px solid #eef2f6; padding: 20px;">
                    <h2 style="margin: 0;">All Products</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b; font-size: 13.5px;">
                        Select checkboxes in sequence to custom order products.
                    </p>
                </div>

                <div class="table-responsive" style="padding: 10px;">
                    <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eef2f6;">
                                <th style="width: 100px; text-align: center;">Select Order</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Category / Badge</th>
                                <th>Price</th>
                                <th>Stock Status</th>
                                <th>Sort Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr style="border-bottom: 1px solid #eef2f6;">
                                    <td style="text-align: center; vertical-align: middle;">
                                        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-width: 60px;">
                                            <input type="checkbox" class="product-selector" data-id="{{ $product->id }}" style="width: 20px; height: 20px; cursor: pointer; accent-color: var(--royal);">
                                            <span class="order-badge" id="badge-{{ $product->id }}" style="display: none;"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="zoomable-image-container" style="width: 60px; height: 45px;" data-zoom-caption="{{ $product->name }}">
                                            <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $product->name }}">
                                            <div class="zoom-overlay">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
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
                                    <td style="font-weight: 600; color: var(--royal); padding-left: 15px;">
                                        {{ $product->sort_order }}
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <button type="button" class="btn-action btn-sm toggle-details" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-specs="{{ $product->description }}" style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(11, 79, 181, 0.1); color: var(--primary); cursor: pointer; font-weight: 600;">
                                                <i class="fas fa-chevron-down"></i> Details
                                            </button>
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

                <!-- Floating action bar at the bottom of the screen -->
                <div id="reorder-toolbar" style="position: fixed; bottom: -100px; left: 50%; transform: translateX(-50%); z-index: 1000; background: white; border-radius: 50px; padding: 12px 30px; box-shadow: 0 10px 30px rgba(9, 32, 58, 0.2); border: 2px solid var(--royal); display: flex; align-items: center; gap: 20px; transition: bottom 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); min-width: 380px; justify-content: space-between;">
                    <span style="font-size: 14.5px; color: var(--dark); font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <span id="selected-count" style="background: var(--royal); color: white; border-radius: 50%; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; box-shadow: 0 2px 5px rgba(11, 79, 181, 0.3);">0</span>
                        <span>Selected for Ordering</span>
                    </span>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <button type="button" id="clear-selection-btn" style="background: none; border: none; color: #64748b; font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: underline; padding: 5px 10px; outline: none;">Clear</button>
                        <button type="submit" class="btn-action btn-primary" style="margin: 0; background: var(--royal); border: none; cursor: pointer; border-radius: 30px; color: white; font-weight: 700; padding: 10px 24px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(11, 79, 181, 0.35); transition: all 0.2s;" onmouseover="this.style.background='#083f91'; this.style.transform='scale(1.03)';" onmouseout="this.style.background='var(--royal)'; this.style.transform='scale(1)';">
                            <i class="fas fa-sort-numeric-down"></i> Apply Selection Order
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <style>
        .order-badge {
            background: var(--royal);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(11, 79, 181, 0.3);
            animation: badgePop 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes badgePop {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }


    </style>
    <script>
        $(document).ready(function() {
            const form = $('#reorder-form');
            
            // Tracking checked items sequence list
            let selectedIds = [];

            // Listen to checkbox changes
            $('.product-selector').on('change', function() {
                const id = $(this).data('id');
                const isChecked = $(this).is(':checked');

                if (isChecked) {
                    if (selectedIds.indexOf(id) === -1) {
                        selectedIds.push(id);
                    }
                } else {
                    selectedIds = selectedIds.filter(item => item !== id);
                }

                updateOrderUI();
            });

            function updateOrderUI() {
                // Hide all badges first
                $('.order-badge').hide();

                // Show and set text of active sequence badges
                selectedIds.forEach((id, index) => {
                    const badge = $('#badge-' + id);
                    badge.text(index + 1);
                    badge.css('display', 'inline-flex');
                });

                // Display/Hide floating toolbar
                const toolbar = $('#reorder-toolbar');
                const selectedCount = $('#selected-count');

                if (selectedIds.length > 0) {
                    selectedCount.text(selectedIds.length);
                    toolbar.css('bottom', '25px');
                } else {
                    toolbar.css('bottom', '-100px');
                }
            }

            // Clear all selected checkboxes
            $('#clear-selection-btn').on('click', function() {
                selectedIds = [];
                $('.product-selector').prop('checked', false);
                $('.order-badge').hide();
                updateOrderUI();
            });

            // Restore checked state and badges when DataTables redraws (pagination, filter, sort)
            $('.datatable').on('draw.dt', function() {
                restoreSelectionState();
            });

            function restoreSelectionState() {
                $('.product-selector').each(function() {
                    const id = $(this).data('id');
                    const index = selectedIds.indexOf(id);
                    const badge = $('#badge-' + id);
                    
                    if (index > -1) {
                        $(this).prop('checked', true);
                        badge.text(index + 1);
                        badge.css('display', 'inline-flex');
                    } else {
                        $(this).prop('checked', false);
                        badge.hide();
                    }
                });
            }

            // Handle form submission: dynamically append all selected inputs so DataTables hidden rows are sent
            form.on('submit', function(e) {
                e.preventDefault();
                
                // Clear any pre-existing dynamic inputs
                form.find('.dynamic-order-input').remove();
                
                // Append all selected orders
                selectedIds.forEach((id, index) => {
                    const sequenceNum = index + 1;
                    form.append(`<input type="hidden" class="dynamic-order-input" name="orders[${id}]" value="${sequenceNum}">`);
                });
                
                // Submit natively
                this.submit();
            });

            // Toggle product specifications detail row
            $(document).on('click', '.toggle-details', function(e) {
                e.preventDefault();
                const btn = $(this);
                const productId = btn.data('id');
                const name = btn.data('name');
                const specs = btn.data('specs');
                const row = btn.closest('tr');
                
                let detailsRow = $('#details-row-' + productId);
                
                if (detailsRow.length) {
                    detailsRow.find('.details-content-wrapper').slideUp(200, function() {
                        detailsRow.remove();
                    });
                    btn.html('<i class="fas fa-chevron-down"></i> Details');
                    btn.removeClass('active');
                } else {
                    let specHtml = '';
                    if (specs && specs.trim()) {
                        let lines = specs.split('\n');
                        specHtml = '<ul style="margin: 0; padding-left: 20px; list-style-type: disc; display: flex; flex-direction: column; gap: 6px;">';
                        lines.forEach(function(line) {
                            if (line.trim()) {
                                specHtml += '<li style="color: #475569; font-size: 13.5px; font-weight: 500; line-height: 1.5;">' + escapeHtml(line) + '</li>';
                            }
                        });
                        specHtml += '</ul>';
                    } else {
                        specHtml = '<span style="color: #94a3b8; font-style: italic; font-size: 13px;">No specifications listed for this product.</span>';
                    }

                    const newRowHtml = `
                        <tr id="details-row-${productId}" class="product-details-row" style="background: #f8fafc; border-bottom: 1px solid #eef2f6;">
                            <td colspan="8" style="padding: 0;">
                                <div class="details-content-wrapper" style="display: none; padding: 20px 25px;">
                                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); border-left: 4px solid var(--royal);">
                                        <h4 style="margin: 0 0 12px 0; color: var(--dark); font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-list-alt" style="color: var(--royal);"></i> Specifications for ${escapeHtml(name)}
                                        </h4>
                                        ${specHtml}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    row.after(newRowHtml);
                    $('#details-row-' + productId).find('.details-content-wrapper').slideDown(250);
                    btn.html('<i class="fas fa-chevron-up"></i> Close');
                    btn.addClass('active');
                }
            });

            function escapeHtml(string) {
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
                $('.product-details-row').remove();
                $('.toggle-details').html('<i class="fas fa-chevron-down"></i> Details').removeClass('active');
            });

            // Export filtered table data to Excel
            $(document).on('click', '#export-excel-btn', function(e) {
                e.preventDefault();
                
                let table = $('.datatable').DataTable();
                let rowsData = table.rows({ search: 'applied' }).nodes().toArray();
                
                if (rowsData.length === 0) {
                    alert('No products available to export.');
                    return;
                }
                
                let html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
                '<head>' +
                    '<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">' +
                    '<style>' +
                        'table { border-collapse: collapse; }' +
                        'th { background-color: #217346; color: white; font-weight: bold; border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-family: sans-serif; }' +
                        'td { border: 1px solid #cbd5e1; padding: 10px; vertical-align: middle; font-family: sans-serif; font-size: 13px; }' +
                    '</style>' +
                '</head>' +
                '<body>' +
                    '<table>' +
                        '<thead>' +
                            '<tr>' +
                                '<th>Product Name</th>' +
                                '<th style="width: 100px; text-align: center;">Image Preview</th>' +
                                '<th>Image URL</th>' +
                                '<th>Category / Badge</th>' +
                                '<th>Price (TZS)</th>' +
                                '<th>Stock Status</th>' +
                                '<th>Sort Order</th>' +
                                '<th style="width: 250px;">Specifications</th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>';

                rowsData.forEach(function(row) {
                    let $row = $(row);
                    
                    let productName = $row.find('td:nth-child(3)').text().trim();
                    let imgUrl = $row.find('td:nth-child(2) img').attr('src') || '';
                    let category = $row.find('td:nth-child(4)').text().trim();
                    let priceText = $row.find('td:nth-child(5)').text().trim();
                    let cleanPrice = priceText.replace(/[^0-9]/g, '');
                    let stockStatus = $row.find('td:nth-child(6)').text().trim();
                    let sortOrder = $row.find('td:nth-child(7)').text().trim();
                    let specs = $row.find('.toggle-details').attr('data-specs') || '';
                    
                    // Clean and format specifications to display nicely inside the excel cell
                    let formattedSpecs = specs ? escapeHtml(specs).replace(/\n/g, '<br>') : 'None';

                    html += '<tr>' +
                        '<td>' + escapeHtml(productName) + '</td>' +
                        '<td style="width: 80px; height: 60px; text-align: center; vertical-align: middle;">' +
                            (imgUrl ? '<img src="' + imgUrl + '" width="60" height="45" style="display: block; margin: auto;" />' : 'No Image') +
                        '</td>' +
                        '<td>' + escapeHtml(imgUrl) + '</td>' +
                        '<td>' + escapeHtml(category) + '</td>' +
                        '<td style="mso-number-format:\\#\\,\\#\\#0;">' + cleanPrice + '</td>' +
                        '<td>' + escapeHtml(stockStatus) + '</td>' +
                        '<td>' + escapeHtml(sortOrder) + '</td>' +
                        '<td>' + formattedSpecs + '</td>' +
                    '</tr>';
                });

                html += '</tbody>' +
                    '</table>' +
                '</body>' +
                '</html>';

                let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                let link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'products_sales_upload_' + new Date().toISOString().split('T')[0] + '.xls';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        });


    </script>
@endsection
