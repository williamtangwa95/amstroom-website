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
                            <th>Sender</th>
                            <th>Contact Info</th>
                            <th>Message</th>
                            <th>Date / Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $msg)
                            <tr style="border-bottom: 1px solid #eef2f6;">
                                <td style="font-weight: 600; color: var(--dark); padding: 12px;">{{ $msg->name }}</td>
                                <td style="padding: 12px;">
                                    <div><i class="fas fa-phone" style="color: #777; width: 15px;"></i> <a href="tel:{{ $msg->phone }}">{{ $msg->phone }}</a></div>
                                    @if($msg->email)
                                        <div style="margin-top: 5px;"><i class="fas fa-envelope" style="color: #777; width: 15px;"></i> <a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></div>
                                    @endif
                                </td>
                                <td style="max-width: 400px; word-wrap: break-word; padding: 12px; line-height: 1.6;">
                                    {!! nl2br(e($msg->message)) !!}
                                </td>
                                <td style="color: #666; font-size: 13px; padding: 12px;">
                                    {{ $msg->created_at->format('M d, Y h:i A') }}<br>
                                    <small style="color: #999;">{{ $msg->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #888; padding: 30px;">
                                    No inquiries found. Contact form submissions will appear here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
