@extends('layouts.admin')

@section('title', 'Security Audit Trail – Yatri Sahayata')
@section('header_title', 'System Security Audit Trail & Access Logs')

@section('content')
<div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem; box-shadow:var(--shadow-sm);">
    <div style="margin-bottom:1.5rem;">
        <h3 style="color:#0a192f; font-size:1.2rem; font-weight:800; margin:0;">OWASP-Compliant Security Logs</h3>
        <span style="font-size:0.8rem; color:#64748b;">Immutable record of all logins, registrations, emergency requests, and status modifications</span>
    </div>

    <div class="table-responsive">
        <table class="yatri-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Action</th>
                    <th>User / Email</th>
                    <th>Target Model</th>
                    <th>IP Address</th>
                    <th>Event Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td style="font-size:0.75rem; color:#64748b; white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i:s A') }}
                    </td>
                    <td>
                        <strong style="color:#0a192f; font-size:0.82rem; font-family:monospace;">{{ $log->action }}</strong>
                    </td>
                    <td>
                        {{ $log->user_email ?? 'System / Anonymous' }}
                    </td>
                    <td>
                        <span class="card-badge badge-info" style="margin:0; font-size:0.7rem;">
                            {{ $log->model_type ?? 'N/A' }} {{ $log->model_id ? '#' . $log->model_id : '' }}
                        </span>
                    </td>
                    <td style="font-family:monospace; font-size:0.78rem;">{{ $log->ip_address }}</td>
                    <td style="font-size:0.82rem; color:#334155;">{{ $log->details }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
