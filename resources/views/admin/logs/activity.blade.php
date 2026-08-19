@extends('layouts.admin')

@section('title', 'User Activity Logs')

@section('content')
    <div class="admin-header">
        <div>
            <h1>User Activity Logs</h1>
            <p style="color: #666; font-size: 14px;">Track system modifications, configurations, and administrative actions.</p>
        </div>
    </div>

    <!-- Filters and Export Bar -->
    <div style="background: white; border-radius: 12px; padding: 15px 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-top: 20px;">
        <form method="GET" action="{{ route('admin.logs.activity') }}" id="filter-form" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin: 0;">
            <!-- User Filter -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="user_id" style="font-size: 13.5px; font-weight: 600; color: #475569;"><i class="fas fa-user-shield" style="color: var(--primary);"></i> User:</label>
                <select name="user_id" id="user_id" class="form-control" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 12px; font-size: 13.5px; cursor: pointer; outline: none; width: auto;">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (string)$userId === (string)$user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Timeframe Filter -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="filter" style="font-size: 13.5px; font-weight: 600; color: #475569;"><i class="fas fa-filter" style="color: var(--primary);"></i> Timeframe:</label>
                <select name="filter" id="filter" class="form-control" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 12px; font-size: 13.5px; cursor: pointer; outline: none; width: auto;" onchange="toggleCustomDateInputs()">
                    <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ ($filter ?? '') === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_month" {{ ($filter ?? '') === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ ($filter ?? '') === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="this_year" {{ ($filter ?? '') === 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom" {{ ($filter ?? '') === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                </select>
            </div>
            
            <div id="custom-date-inputs" style="display: {{ ($filter ?? '') === 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 10px;">
                <input type="date" name="start_date" id="start_date" class="form-control" style="padding: 5px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;" value="{{ request('start_date') }}">
                <span style="color: #64748b; font-size: 13px;">to</span>
                <input type="date" name="end_date" id="end_date" class="form-control" style="padding: 5px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;" value="{{ request('end_date') }}">
            </div>
            
            <button type="submit" class="btn-action btn-primary" style="padding: 7px 15px; border-radius: 6px; font-size: 13px; font-weight: 600;"><i class="fas fa-sync-alt"></i> Apply Filter</button>
        </form>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" id="export-excel-btn" class="btn-action" style="background: #217346; color: white; border: none; cursor: pointer; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; font-size: 13.5px; transition: all 0.2s;" onmouseover="this.style.background='#1a5c38'" onmouseout="this.style.background='#217346'">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <button type="button" id="export-pdf-btn" class="btn-action" style="background: #e11d48; color: white; border: none; cursor: pointer; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; font-size: 13.5px; transition: all 0.2s;" onmouseover="this.style.background='#be123c'" onmouseout="this.style.background='#e11d48'">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
        </div>
    </div>

    <!-- Activity Log List -->
    <div class="dashboard-card" style="margin-top: 25px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); padding: 20px;">
        <div class="card-header" style="border-bottom: 1px solid #eef2f6; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 18px; font-weight: 600; color: var(--dark);">System Activity Timeline (Last 1000 logs)</h2>
        </div>

        <div style="overflow-x: auto;">
            <table class="table datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eef2f6;">
                        <th style="padding: 12px; width: 150px;">Time</th>
                        <th style="padding: 12px; width: 140px;">User</th>
                        <th style="padding: 12px; width: 120px;">Action</th>
                        <th style="padding: 12px;">Activity Details</th>
                        <th style="padding: 12px; width: 180px;">Origin info</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        @php
                            $actionColors = [
                                'created' => 'background: #dcfce7; color: #15803d;',
                                'updated' => 'background: #fef9c3; color: #a16207;',
                                'deleted' => 'background: #fee2e2; color: #b91c1c;',
                                'login' => 'background: #e0f2fe; color: #0369a1;',
                                'logout' => 'background: #f1f5f9; color: #475569;',
                            ];
                            
                            $actionBadges = [
                                'created' => 'fa-plus-circle',
                                'updated' => 'fa-edit',
                                'deleted' => 'fa-trash-alt',
                                'login' => 'fa-sign-in-alt',
                                'logout' => 'fa-sign-out-alt',
                            ];
                        @endphp
                        <tr style="border-bottom: 1px solid #eef2f6; font-size: 13.5px; vertical-align: top;">
                            <!-- Timestamp -->
                            <td style="padding: 12px; color: #555;" data-order="{{ $log->created_at->timestamp }}">
                                <div style="font-weight: 500;">{{ $log->created_at->format('M d, H:i:s') }}</div>
                                <div style="font-size: 11px; color: #888;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- User Info -->
                            <td style="padding: 12px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="background: var(--primary); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 11px;">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #334155;">{{ $log->user->name ?? 'System' }}</div>
                                        <div style="font-size: 11px; color: #64748b; text-transform: capitalize;">{{ $log->user->role ?? 'system' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Action Badge -->
                            <td style="padding: 12px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 11.5px; text-transform: uppercase; {{ $actionColors[$log->action] ?? 'background: #f1f5f9; color: #334155;' }}">
                                    <i class="fas {{ $actionBadges[$log->action] ?? 'fa-info-circle' }}" style="font-size: 11px;"></i>
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Log details / changes tracking -->
                            <td style="padding: 12px;">
                                <div style="font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                                    {{ $log->details['message'] ?? 'No details provided.' }}
                                </div>
                                
                                <!-- Render changes for updates -->
                                @if($log->action === 'updated' && isset($log->details['before']) && isset($log->details['after']))
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; margin-top: 5px; font-size: 12.5px;">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <thead>
                                                <tr style="border-bottom: 1px solid #cbd5e1; text-align: left; font-size: 11px; color: #64748b; text-transform: uppercase;">
                                                    <th style="padding-bottom: 4px;">Field</th>
                                                    <th style="padding-bottom: 4px;">Old Value</th>
                                                    <th style="padding-bottom: 4px;">New Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($log->details['after'] as $key => $afterValue)
                                                    @php
                                                        $beforeValue = $log->details['before'][$key] ?? '';
                                                    @endphp
                                                    <tr style="border-bottom: 1px dashed #e2e8f0;">
                                                        <td style="font-weight: 600; color: #475569; padding: 6px 0; font-family: monospace;">{{ $key }}</td>
                                                        <td style="color: #b91c1c; background: #fee2e2; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; font-family: monospace; text-decoration: line-through; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            {{ is_array($beforeValue) ? json_encode($beforeValue) : ($beforeValue ?: '[empty]') }}
                                                        </td>
                                                        <td style="color: #15803d; background: #dcfce7; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; margin-left: 10px; font-family: monospace; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            {{ is_array($afterValue) ? json_encode($afterValue) : ($afterValue ?: '[empty]') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <!-- Render attributes for creation/deletion -->
                                @if(in_array($log->action, ['created', 'deleted']) && isset($log->details['attributes']))
                                    <details style="margin-top: 5px;">
                                        <summary style="font-size: 12px; color: var(--primary); cursor: pointer; font-weight: 500;">View properties</summary>
                                        <pre style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; font-size: 11.5px; font-family: monospace; overflow-x: auto; margin-top: 5px; color: #475569;">{{ json_encode($log->details['attributes'], JSON_PRETTY_PRINT) }}</pre>
                                    </details>
                                @endif
                            </td>

                            <!-- Origin (IP / User-Agent) -->
                            <td style="padding: 12px; color: #64748b;">
                                <div style="font-weight: 500; color: #475569;"><i class="fas fa-network-wired" style="font-size: 11px; margin-right: 4px;"></i> {{ $log->ip_address }}</div>
                                <div style="font-size: 11px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @section('scripts')
        <script>
            function toggleCustomDateInputs() {
                const filterVal = document.getElementById('filter').value;
                const customDiv = document.getElementById('custom-date-inputs');
                if (filterVal === 'custom') {
                    customDiv.style.display = 'flex';
                } else {
                    customDiv.style.display = 'none';
                }
            }

            $(document).ready(function() {
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

                // Export to Excel
                $(document).on('click', '#export-excel-btn', function(e) {
                    e.preventDefault();
                    let table = $('.datatable').DataTable();
                    let rowsData = table.rows({ search: 'applied' }).nodes().toArray();
                    
                    if (rowsData.length === 0) {
                        alert('No logs available to export.');
                        return;
                    }
                    
                    let html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
                    '<head>' +
                        '<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">' +
                        '<style>' +
                            'table { border-collapse: collapse; }' +
                            'th { background-color: #217346; color: white; font-weight: bold; border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-family: sans-serif; }' +
                            'td { border: 1px solid #cbd5e1; padding: 10px; vertical-align: top; font-family: sans-serif; font-size: 13px; }' +
                        '</style>' +
                    '</head>' +
                    '<body>' +
                        '<table>' +
                            '<thead>' +
                                '<tr>' +
                                    '<th>Time</th>' +
                                    '<th>User Name</th>' +
                                    '<th>User Role</th>' +
                                    '<th>Action</th>' +
                                    '<th style="width: 350px;">Activity Details</th>' +
                                    '<th>IP Address</th>' +
                                    '<th>User Agent</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>';

                    rowsData.forEach(function(row) {
                        let $row = $(row);
                        let time = $row.find('td:nth-child(1) div:first-child').text().trim();
                        let userName = $row.find('td:nth-child(2) div[style*="font-weight: 600"]').text().trim() || $row.find('td:nth-child(2)').text().trim();
                        let userRole = $row.find('td:nth-child(2) div[style*="font-size: 11px"]').text().trim() || 'System';
                        let action = $row.find('td:nth-child(3)').text().trim();
                        
                        let detailsObj = $row.find('td:nth-child(4)').clone();
                        detailsObj.find('summary').remove();
                        let detailsHtml = detailsObj.html().trim();

                        let ip = $row.find('td:nth-child(5) div:first-child').text().trim();
                        let ua = $row.find('td:nth-child(5) div:last-child').text().trim();

                        html += '<tr>' +
                            '<td>' + escapeHtml(time) + '</td>' +
                            '<td>' + escapeHtml(userName) + '</td>' +
                            '<td>' + escapeHtml(userRole) + '</td>' +
                            '<td>' + escapeHtml(action) + '</td>' +
                            '<td>' + detailsHtml + '</td>' +
                            '<td>' + escapeHtml(ip) + '</td>' +
                            '<td>' + escapeHtml(ua) + '</td>' +
                        '</tr>';
                    });

                    html += '</tbody>' +
                        '</table>' +
                    '</body>' +
                    '</html>';

                    let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                    let link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'user_activity_logs_' + new Date().toISOString().split('T')[0] + '.xls';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });

                // Export to PDF (Print-friendly format)
                $(document).on('click', '#export-pdf-btn', function(e) {
                    e.preventDefault();
                    let table = $('.datatable').DataTable();
                    let rowsData = table.rows({ search: 'applied' }).nodes().toArray();
                    
                    if (rowsData.length === 0) {
                        alert('No logs available to export.');
                        return;
                    }
                    
                    let printWindow = window.open('', '_blank');
                    let html = '<html>' +
                    '<head>' +
                        '<title>System User Activity Log Report</title>' +
                        '<style>' +
                            'body { font-family: system-ui, -apple-system, sans-serif; color: #333; margin: 30px; }' +
                            'h1 { color: #0b4fb5; font-size: 24px; margin-bottom: 5px; }' +
                            'p { color: #666; font-size: 14px; margin-top: 0; margin-bottom: 20px; }' +
                            'table { width: 100%; border-collapse: collapse; margin-top: 15px; }' +
                            'th { background-color: #f1f5f9; color: #1e293b; font-weight: 600; border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-size: 12px; text-transform: uppercase; }' +
                            'td { border: 1px solid #cbd5e1; padding: 10px; vertical-align: top; font-size: 12px; }' +
                            '.badge { font-weight: bold; padding: 3px 8px; border-radius: 12px; font-size: 10.5px; display: inline-flex; text-transform: uppercase; }' +
                            '.badge-created { background: #dcfce7; color: #15803d; }' +
                            '.badge-updated { background: #fef9c3; color: #a16207; }' +
                            '.badge-deleted { background: #fee2e2; color: #b91c1c; }' +
                            '.badge-login { background: #e0f2fe; color: #0369a1; }' +
                            '.badge-logout { background: #f1f5f9; color: #475569; }' +
                            '@media print { body { margin: 15px; } button { display: none; } }' +
                        '</style>' +
                    '</head>' +
                    '<body>' +
                        '<h1>System User Activity Log Report</h1>' +
                        '<p>Generated on: ' + new Date().toLocaleString() + '</p>' +
                        '<table>' +
                            '<thead>' +
                                '<tr>' +
                                    '<th>Time</th>' +
                                    '<th>User Account</th>' +
                                    '<th>Action</th>' +
                                    '<th style="width: 45%;">Activity Details</th>' +
                                    '<th>Origin Info</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>';

                    rowsData.forEach(function(row) {
                        let $row = $(row);
                        let time = $row.find('td:nth-child(1) div:first-child').text().trim();
                        let userName = $row.find('td:nth-child(2) div[style*="font-weight: 600"]').text().trim() || $row.find('td:nth-child(2)').text().trim();
                        let userRole = $row.find('td:nth-child(2) div[style*="font-size: 11px"]').text().trim() || 'System';
                        let action = $row.find('td:nth-child(3)').text().trim();
                        
                        let detailsObj = $row.find('td:nth-child(4)').clone();
                        detailsObj.find('summary').remove();
                        let detailsHtml = detailsObj.html().trim();

                        let ip = $row.find('td:nth-child(5) div:first-child').text().trim();
                        let ua = $row.find('td:nth-child(5) div:last-child').text().trim();

                        let actionClass = 'badge-' + action.toLowerCase();

                        html += '<tr>' +
                            '<td>' + escapeHtml(time) + '</td>' +
                            '<td><strong>' + escapeHtml(userName) + '</strong><br><span style="font-size:10px; color:#666;">' + escapeHtml(userRole) + '</span></td>' +
                            '<td><span class="badge ' + actionClass + '">' + escapeHtml(action) + '</span></td>' +
                            '<td>' + detailsHtml + '</td>' +
                            '<td>' + escapeHtml(ip) + '<br><span style="font-size:10px; color:#666;">' + escapeHtml(ua) + '</span></td>' +
                        '</tr>';
                    });

                    html += '</tbody>' +
                        '</table>' +
                        '<script>window.onload = function() { window.print(); window.close(); };<\/script>' +
                    '</body>' +
                    '</html>';

                    printWindow.document.open();
                    printWindow.document.write(html);
                    printWindow.document.close();
                });
            });
        </script>
    @endsection
@endsection
