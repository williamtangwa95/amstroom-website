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
                                        <div class="product-img-container" style="position: relative; width: 60px; height: 45px; border-radius: 6px; overflow: hidden; cursor: pointer; display: inline-block;" onclick="openZoomModal('{{ $product->image_url ?? 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80' }}')">
                                            <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80' }}" alt="" class="product-img-th" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.2s ease-in-out;">
                                            <div class="zoom-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s ease-in-out; border-radius: 6px;">
                                                <i class="fas fa-search-plus" style="color: white; font-size: 14px;"></i>
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

    <!-- Zoom Lightbox Modal -->
    <div id="zoomModal" class="zoom-modal">
        <span class="zoom-close" onclick="closeZoomModal()">&times;</span>
        <div style="max-width: 90%; max-height: 90%; display: flex; align-items: center; justify-content: center; position: relative;">
            <img class="zoom-content" id="zoomImg" alt="Product Image Zoomed">
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

        .product-img-container:hover .zoom-overlay {
            opacity: 1 !important;
        }
        .product-img-container:hover .product-img-th {
            transform: scale(1.1);
        }
        .zoom-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.25s ease-out;
        }
        .zoom-modal.active {
            display: flex !important;
            opacity: 1;
        }
        .zoom-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            user-select: none;
        }
        .zoom-close:hover {
            color: #cbd5e1 !important;
        }
        .zoom-content {
            margin: auto;
            display: block;
            max-width: 100%;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            object-fit: contain;
            animation-name: zoomIn;
            animation-duration: 0.25s;
        }
        @keyframes zoomIn {
            from {transform: scale(0.9); opacity: 0;}
            to {transform: scale(1); opacity: 1;}
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
        });

        function openZoomModal(imgSrc) {
            const modal = document.getElementById('zoomModal');
            const modalImg = document.getElementById('zoomImg');
            if (modal && modalImg) {
                modalImg.src = imgSrc;
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('active');
                }, 10);
                document.body.style.overflow = 'hidden';
            }
        }

        function closeZoomModal() {
            const modal = document.getElementById('zoomModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 250);
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeZoomModal();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('zoomModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal || e.target.classList.contains('zoom-close')) {
                        closeZoomModal();
                    }
                });
            }
        });
    </script>
@endsection
