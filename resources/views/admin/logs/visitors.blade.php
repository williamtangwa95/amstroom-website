@extends('layouts.admin')

@section('title', 'Visitor Analytics')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Visitor Analytics</h1>
            <p style="color: #666; font-size: 14px;">Track visitor sessions, location demographics, and devices.</p>
        </div>
    </div>

    <!-- Analytics Dashboard Cards -->
    <div class="metrics-grid" style="margin-top: 25px; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
        <div class="metric-card" style="background: white; border-top: 5px solid var(--primary); padding: 20px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: #64748b; font-size: 14px; margin-bottom: 5px;">Total Page Views</h3>
                <p style="font-size: 28px; font-weight: 700; margin: 0; color: var(--dark);">{{ number_format($totalViews) }}</p>
            </div>
            <div style="font-size: 32px; color: var(--primary);"><i class="fas fa-eye"></i></div>
        </div>

        <div class="metric-card" style="background: white; border-top: 5px solid #10b981; padding: 20px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: #64748b; font-size: 14px; margin-bottom: 5px;">Unique Visitors</h3>
                <p style="font-size: 28px; font-weight: 700; margin: 0; color: var(--dark);">{{ number_format($uniqueVisitors) }}</p>
            </div>
            <div style="font-size: 32px; color: #10b981;"><i class="fas fa-users"></i></div>
        </div>

        <div class="metric-card" style="background: white; border-top: 5px solid #f59e0b; padding: 20px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: #64748b; font-size: 14px; margin-bottom: 5px;">Top Device</h3>
                <p style="font-size: 22px; font-weight: 700; margin: 0; color: var(--dark); text-transform: capitalize;">
                    {{ $deviceStats->first()->device_type ?? 'None' }}
                </p>
            </div>
            <div style="font-size: 32px; color: #f59e0b;">
                @php
                    $device = $deviceStats->first()->device_type ?? 'desktop';
                @endphp
                @if($device === 'mobile')
                    <i class="fas fa-mobile-alt"></i>
                @elseif($device === 'tablet')
                    <i class="fas fa-tablet-alt"></i>
                @else
                    <i class="fas fa-desktop"></i>
                @endif
            </div>
        </div>

        <div class="metric-card" style="background: white; border-top: 5px solid #6366f1; padding: 20px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: #64748b; font-size: 14px; margin-bottom: 5px;">Top Country</h3>
                <p style="font-size: 22px; font-weight: 700; margin: 0; color: var(--dark);">
                    {{ $topLocations->first()->country ?? 'None' }}
                </p>
            </div>
            <div style="font-size: 32px; color: #6366f1;"><i class="fas fa-globe-africa"></i></div>
        </div>
    </div>

    <!-- Breakdown Analytics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 30px;">
        
        <!-- Top Locations -->
        <div class="dashboard-card" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); padding: 20px;">
            <div class="card-header" style="border-bottom: 1px solid #eef2f6; padding-bottom: 15px; margin-bottom: 15px;">
                <h2 style="font-size: 16px; font-weight: 600; color: var(--dark);"><i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 8px;"></i> Top Visitor Locations</h2>
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($topLocations as $loc)
                    @php
                        $percentage = $totalViews > 0 ? ($loc->count / $totalViews) * 100 : 0;
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; font-weight: 500; margin-bottom: 5px; color: #334155;">
                            <span>{{ $loc->city ?: 'Unknown' }}, {{ $loc->country }}</span>
                            <span style="font-weight: 600;">{{ $loc->count }} hits ({{ round($percentage, 1) }}%)</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: var(--primary); width: {{ $percentage }}%; height: 100%; border-radius: 4px;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color: #64748b; font-size: 13.5px; text-align: center; padding: 20px 0;">No location data available</p>
                @endforelse
            </div>
        </div>

        <!-- Devices & Browsers -->
        <div class="dashboard-card" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); padding: 20px;">
            <div class="card-header" style="border-bottom: 1px solid #eef2f6; padding-bottom: 15px; margin-bottom: 15px;">
                <h2 style="font-size: 16px; font-weight: 600; color: var(--dark);"><i class="fas fa-laptop" style="color: #10b981; margin-right: 8px;"></i> Device & Browser Stats</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Devices -->
                <div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Devices</h4>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @forelse($deviceStats as $stat)
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #334155;">
                                <span style="text-transform: capitalize;">
                                    @if($stat->device_type === 'mobile')
                                        <i class="fas fa-mobile-alt" style="width: 15px; color: #888;"></i>
                                    @elseif($stat->device_type === 'tablet')
                                        <i class="fas fa-tablet-alt" style="width: 15px; color: #888;"></i>
                                    @else
                                        <i class="fas fa-desktop" style="width: 15px; color: #888;"></i>
                                    @endif
                                    {{ $stat->device_type }}
                                </span>
                                <span style="font-weight: 600; background: #f1f5f9; padding: 2px 8px; border-radius: 10px;">{{ $stat->count }}</span>
                            </div>
                        @empty
                            <p style="color: #888; font-size: 12px;">No device data</p>
                        @endforelse
                    </div>
                </div>

                <!-- Browsers -->
                <div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Top Browsers</h4>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @forelse($browserStats as $stat)
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #334155;">
                                <span>
                                    @if($stat->browser === 'Chrome')
                                        <i class="fab fa-chrome" style="width: 15px; color: #ea4335;"></i>
                                    @elseif($stat->browser === 'Firefox')
                                        <i class="fab fa-firefox-browser" style="width: 15px; color: #ff9400;"></i>
                                    @elseif($stat->browser === 'Safari')
                                        <i class="fab fa-safari" style="width: 15px; color: #00a2ed;"></i>
                                    @elseif($stat->browser === 'Edge')
                                        <i class="fab fa-edge" style="width: 15px; color: #0078d7;"></i>
                                    @else
                                        <i class="fas fa-window-maximize" style="width: 15px; color: #888;"></i>
                                    @endif
                                    {{ $stat->browser }}
                                </span>
                                <span style="font-weight: 600; background: #f1f5f9; padding: 2px 8px; border-radius: 10px;">{{ $stat->count }}</span>
                            </div>
                        @empty
                            <p style="color: #888; font-size: 12px;">No browser data</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Hits Table -->
    <div class="dashboard-card" style="margin-top: 40px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); padding: 20px;">
        <div class="card-header" style="border-bottom: 1px solid #eef2f6; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 18px; font-weight: 600; color: var(--dark);">Visitor Request Log (Last 1000 hits)</h2>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="table datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eef2f6;">
                        <th style="padding: 12px;">Time</th>
                        <th style="padding: 12px;">IP Address</th>
                        <th style="padding: 12px;">Location</th>
                        <th style="padding: 12px;">Device / Browser</th>
                        <th style="padding: 12px;">Request</th>
                        <th style="padding: 12px;">User Account</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        @php
                            $urlPath = parse_url($log->url, PHP_URL_PATH) ?: '/';
                        @endphp
                        <tr style="border-bottom: 1px solid #eef2f6; font-size: 13.5px;">
                            <td style="padding: 12px; color: #555;" data-order="{{ $log->created_at->timestamp }}">
                                {{ $log->created_at->format('M d, H:i:s') }}
                                <div style="font-size: 11px; color: #888;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td style="padding: 12px; font-weight: 500; color: #334155;">
                                {{ $log->ip_address }}
                            </td>
                            <td style="padding: 12px; color: #555;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-map-marker-alt" style="color: #94a3b8; font-size: 12px;"></i>
                                    <span>{{ $log->city ?: 'Unknown' }}, {{ $log->country }}</span>
                                </div>
                            </td>
                            <td style="padding: 12px; color: #555;">
                                <div style="display: flex; flex-direction: column;">
                                    <span style="text-transform: capitalize; font-weight: 500;">
                                        @if($log->device_type === 'mobile')
                                            <i class="fas fa-mobile-alt" style="color: #64748b; font-size: 11px; width: 12px;"></i>
                                        @elseif($log->device_type === 'tablet')
                                            <i class="fas fa-tablet-alt" style="color: #64748b; font-size: 11px; width: 12px;"></i>
                                        @else
                                            <i class="fas fa-desktop" style="color: #64748b; font-size: 11px; width: 12px;"></i>
                                        @endif
                                        {{ $log->platform }}
                                    </span>
                                    <span style="font-size: 11px; color: #888;">{{ $log->browser }}</span>
                                </div>
                            </td>
                            <td style="padding: 12px; color: #334155;">
                                <span style="font-weight: 700; font-size: 11px; padding: 2px 6px; border-radius: 4px; background: {{ $log->method === 'POST' ? '#dcfce7; color: #15803d;' : '#e0f2fe; color: #0369a1;' }};">
                                    {{ $log->method }}
                                </span>
                                <span style="font-weight: 500; font-family: monospace; font-size: 12.5px; margin-left: 5px;" title="{{ $log->url }}">
                                    {{ $urlPath }}
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                @if($log->user)
                                    <span style="background: rgba(11, 79, 181, 0.1); color: var(--primary); padding: 4px 10px; border-radius: 10px; font-weight: 600; font-size: 12px;">
                                        <i class="fas fa-user-shield" style="font-size: 10px; margin-right: 4px;"></i> {{ $log->user->name }}
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Guest</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
