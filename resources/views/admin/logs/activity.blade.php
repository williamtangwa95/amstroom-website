@extends('layouts.admin')

@section('title', 'User Activity Logs')

@section('content')
    <div class="admin-header">
        <div>
            <h1>User Activity Logs</h1>
            <p style="color: #666; font-size: 14px;">Track system modifications, configurations, and administrative actions.</p>
        </div>
    </div>

    <!-- Activity Log List -->
    <div class="dashboard-card" style="margin-top: 30px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); padding: 20px;">
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
@endsection
