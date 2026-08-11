@extends('layouts.admin')

@section('title', 'Homepage Content Manager')

@section('styles')
<style>
    /* Tab Styling */
    .tab-container {
        margin-top: 30px;
    }
    .tab-headers {
        display: flex;
        border-bottom: 2px solid #eef2f6;
        gap: 15px;
        margin-bottom: 25px;
    }
    .tab-btn {
        padding: 12px 25px;
        font-weight: 600;
        font-size: 15px;
        color: #64748b;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: 0.3s;
        outline: none;
    }
    .tab-btn:hover {
        color: var(--royal);
    }
    .tab-btn.active {
        color: var(--royal);
        border-bottom-color: var(--royal);
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    
    .comp-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(11, 79, 181, 0.08);
        color: var(--royal);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
</style>
@endsection

@section('content')
    <div class="admin-header">
        <div>
            <h1>Homepage Content Manager</h1>
            <p style="color: #666; font-size: 14px;">Customize dynamic homepage blocks (Services, Why Choose Us, and Stats Counter).</p>
        </div>
    </div>

    <div class="tab-container">
        <!-- Tab Headers -->
        <div class="tab-headers">
            <button class="tab-btn active" onclick="switchTab(event, 'services-tab')"><i class="fas fa-laptop-code"></i> Services List</button>
            <button class="tab-btn" onclick="switchTab(event, 'why-tab')"><i class="fas fa-shield-halved"></i> Why Choose Us</button>
            <button class="tab-btn" onclick="switchTab(event, 'stats-tab')"><i class="fas fa-chart-bar"></i> Stats Counters</button>
        </div>

        <!-- Tab 1: Services -->
        <div id="services-tab" class="tab-content active">
            <div class="dashboard-card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Services Catalog</h2>
                    <a href="{{ route('admin.services.create') }}" class="btn-action btn-primary btn-sm" style="font-size: 13px;"><i class="fas fa-plus"></i> Add Service</a>
                </div>
                <div class="table-responsive" style="padding: 10px;">
                    <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eef2f6;">
                                <th style="width: 60px;">Icon</th>
                                <th>Service Title</th>
                                <th>Description</th>
                                <th style="width: 100px;">Sort Order</th>
                                <th style="width: 180px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $srv)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td>
                                    <div class="comp-icon-box">
                                        <i class="{{ $srv->icon }}"></i>
                                    </div>
                                </td>
                                <td style="font-weight: 600; color: var(--dark);">{{ $srv->title }}</td>
                                <td style="color: #555; font-size: 13.5px;">{{ $srv->description }}</td>
                                <td>{{ $srv->sort_order }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.services.edit', $srv->id) }}" class="btn-action btn-sm btn-edit" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: #333;"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('admin.services.delete', $srv->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" style="margin: 0; display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-sm btn-danger" style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(220, 53, 69, 0.1); color: #dc3545; cursor: pointer;">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: #888;">No services defined. Click "Add Service" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Why Choose Us -->
        <div id="why-tab" class="tab-content">
            <div class="dashboard-card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Features & Strengths</h2>
                    <a href="{{ route('admin.why-chooses.create') }}" class="btn-action btn-primary btn-sm" style="font-size: 13px;"><i class="fas fa-plus"></i> Add Feature</a>
                </div>
                <div class="table-responsive" style="padding: 10px;">
                    <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eef2f6;">
                                <th style="width: 60px;">Icon</th>
                                <th>Feature Title</th>
                                <th>Description</th>
                                <th style="width: 100px;">Sort Order</th>
                                <th style="width: 180px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($whyChooses as $wc)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td>
                                    <div class="comp-icon-box" style="background: rgba(40,167,69,0.08); color: #28a745;">
                                        <i class="{{ $wc->icon }}"></i>
                                    </div>
                                </td>
                                <td style="font-weight: 600; color: var(--dark);">{{ $wc->title }}</td>
                                <td style="color: #555; font-size: 13.5px;">{{ $wc->description }}</td>
                                <td>{{ $wc->sort_order }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.why-chooses.edit', $wc->id) }}" class="btn-action btn-sm btn-edit" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: #333;"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('admin.why-chooses.delete', $wc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this feature?');" style="margin: 0; display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-sm btn-danger" style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(220, 53, 69, 0.1); color: #dc3545; cursor: pointer;">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: #888;">No features defined. Click "Add Feature" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 3: Stats -->
        <div id="stats-tab" class="tab-content">
            <div class="dashboard-card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Statistics & Achievements</h2>
                    <a href="{{ route('admin.stats.create') }}" class="btn-action btn-primary btn-sm" style="font-size: 13px;"><i class="fas fa-plus"></i> Add Stat</a>
                </div>
                <div class="table-responsive" style="padding: 10px;">
                    <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eef2f6;">
                                <th style="width: 150px;">Metric Value</th>
                                <th>Label Name</th>
                                <th style="width: 100px;">Sort Order</th>
                                <th style="width: 180px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats as $st)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="font-weight: 700; color: var(--royal); font-size: 16px;">{{ $st->value }}</td>
                                <td style="font-weight: 600; color: var(--dark);">{{ $st->label }}</td>
                                <td>{{ $st->sort_order }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.stats.edit', $st->id) }}" class="btn-action btn-sm btn-edit" style="display: inline-block; padding: 6px 12px; font-size: 13px; text-decoration: none; border-radius: 6px; background: #eef2f6; color: #333;"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('admin.stats.delete', $st->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this stat?');" style="margin: 0; display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-sm btn-danger" style="display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: rgba(220, 53, 69, 0.1); color: #dc3545; cursor: pointer;">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 30px; color: #888;">No stats defined. Click "Add Stat" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(evt, tabId) {
            // Hide all contents
            const contents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < contents.length; i++) {
                contents[i].classList.remove("active");
            }
            
            // Deactivate all buttons
            const buttons = document.getElementsByClassName("tab-btn");
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove("active");
            }
            
            // Show current tab & activate current button
            document.getElementById(tabId).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
@endsection
