@extends('layouts.admin')

@section('title', 'Customer Inquiries')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Customer Inquiries</h1>
            <p style="color: #666; font-size: 14px;">View queries and contact submissions sent by visitors on the front-end page.</p>
        </div>
    </div>

    <!-- Contact Messages Section -->
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>All Inquiries</h2>
            </div>
            <div class="table-responsive" style="padding: 10px;">
                <table class="datatable" style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eef2f6;">
                            <th style="padding: 12px;">Sender</th>
                            <th style="padding: 12px;">Contact Info</th>
                            <th style="padding: 12px;">Message</th>
                            <th style="padding: 12px;">Date / Time</th>
                            <th style="padding: 12px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $msg)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="font-weight: 600; color: var(--dark); padding: 12px;">{{ $msg->name }}</td>
                                <td style="padding: 12px;">
                                    <div id="disabled-contacts-{{ $msg->id }}">
                                        <div>
                                            <i class="fas fa-phone" style="color: #bbb; width: 15px;"></i> 
                                            <span style="color: #999; cursor: not-allowed;" title="Click 'Enable' in actions to make clickable">{{ $msg->phone }}</span>
                                        </div>
                                        @if($msg->email)
                                            <div style="margin-top: 5px;">
                                                <i class="fas fa-envelope" style="color: #bbb; width: 15px;"></i> 
                                                <span style="color: #999; cursor: not-allowed;" title="Click 'Enable' in actions to make clickable">{{ $msg->email }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div id="enabled-contacts-{{ $msg->id }}" style="display: none;">
                                        <div>
                                            <i class="fas fa-phone" style="color: var(--primary, #0B4FB5); width: 15px;"></i> 
                                            <a href="tel:{{ $msg->phone }}" style="color: var(--primary, #0B4FB5); font-weight: 600; text-decoration: none;">{{ $msg->phone }}</a>
                                        </div>
                                        @if($msg->email)
                                            <div style="margin-top: 5px;">
                                                <i class="fas fa-envelope" style="color: var(--primary, #0B4FB5); width: 15px;"></i> 
                                                <a href="mailto:{{ $msg->email }}" style="color: var(--primary, #0B4FB5); font-weight: 600; text-decoration: none;">{{ $msg->email }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td style="max-width: 400px; word-wrap: break-word; padding: 12px; line-height: 1.6;">
                                    {!! nl2br(e($msg->message)) !!}
                                </td>
                                <td style="color: #666; font-size: 13px; padding: 12px;">
                                    {{ $msg->created_at->format('M d, Y h:i A') }}<br>
                                    <small style="color: #999;">{{ $msg->created_at->diffForHumans() }}</small>
                                </td>
                                <td style="padding: 12px; text-align: center; white-space: nowrap;">
                                    <!-- Enable/Unlock Button -->
                                    <button type="button" id="btn-enable-{{ $msg->id }}" onclick="enableContactLinks({{ $msg->id }})" class="btn-action" style="padding: 6px 12px; background: #e2e8f0; color: #334155; border: none; border-radius: 6px; font-size: 12.5px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s;" onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'">
                                        <i class="fas fa-unlock" style="font-size: 11px;"></i> Enable
                                    </button>
                                    
                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.inquiries.delete', $msg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?')" style="margin: 0; display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-logout-sidebar" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 16px; padding: 5px 10px; transition: 0.2s; vertical-align: middle;" title="Delete Inquiry" onmouseover="this.style.color='#900'" onmouseout="this.style.color='#dc3545'">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function enableContactLinks(id) {
        const disabledDiv = document.getElementById('disabled-contacts-' + id);
        const enabledDiv = document.getElementById('enabled-contacts-' + id);
        const btn = document.getElementById('btn-enable-' + id);
        
        if (disabledDiv && enabledDiv && btn) {
            if (disabledDiv.style.display !== 'none') {
                disabledDiv.style.display = 'none';
                enabledDiv.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-lock" style="font-size: 11px;"></i> Disable';
                btn.style.background = '#fee2e2';
                btn.style.color = '#991b1b';
                btn.onmouseover = function() { this.style.background = '#fecaca'; };
                btn.onmouseout = function() { this.style.background = '#fee2e2'; };
            } else {
                disabledDiv.style.display = 'block';
                enabledDiv.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-unlock" style="font-size: 11px;"></i> Enable';
                btn.style.background = '#e2e8f0';
                btn.style.color = '#334155';
                btn.onmouseover = function() { this.style.background = '#cbd5e1'; };
                btn.onmouseout = function() { this.style.background = '#e2e8f0'; };
            }
        }
    }
</script>
@endsection
