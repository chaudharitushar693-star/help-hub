@extends('layouts.app')

@section('title', 'Assistance Request Status Tracker – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:800px;">
        <div class="section-header">
            <span class="section-tag">Live Request Tracking</span>
            <h1 class="section-title">Track Assistance Request Status</h1>
            <p class="section-desc">Enter your unique 16-character tracking code (e.g. YS-REQ-202611-1001) to view real-time volunteer and sevadar updates.</p>
        </div>

        <!-- Search Tracker Form -->
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem; margin-bottom:2.5rem;">
            <form action="{{ route('assistance.track') }}" method="GET" style="display:flex; gap:0.75rem;">
                <input type="text" name="code" class="form-control" placeholder="Enter Tracking Code (e.g. YS-REQ-202611-1001)" value="{{ $code ?? '' }}" required>
                <button type="submit" class="btn btn-primary" style="white-space:nowrap;">Track Case</button>
            </form>
        </div>

        @if($requestData)
            <!-- Request Card & Timeline -->
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:2rem; box-shadow:var(--shadow-md);">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; border-bottom:1px solid #f1f5f9; padding-bottom:1.25rem;">
                    <div>
                        <span style="font-size:0.8rem; color:#64748b; text-transform:uppercase;">Tracking Code</span>
                        <h2 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0.15rem 0;">{{ $requestData->tracking_code }}</h2>
                        <div style="font-size:0.85rem; color:#64748b;">
                            Logged: {{ \Carbon\Carbon::parse($requestData->created_at)->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <div>
                        @if($requestData->status === 'resolved')
                            <span class="card-badge badge-verified" style="font-size:0.9rem; padding:0.4rem 1rem;">✅ RESOLVED</span>
                        @elseif($requestData->status === 'in_progress')
                            <span class="card-badge badge-gold" style="font-size:0.9rem; padding:0.4rem 1rem;">⏳ IN PROGRESS</span>
                        @elseif($requestData->status === 'acknowledged')
                            <span class="card-badge badge-info" style="font-size:0.9rem; padding:0.4rem 1rem;">👁️ ACKNOWLEDGED</span>
                        @else
                            <span class="card-badge badge-emergency" style="font-size:0.9rem; padding:0.4rem 1rem;">🚨 PENDING DISPATCH</span>
                        @endif
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; font-size:0.9rem; margin-bottom:1.5rem;">
                    <div><strong>Pilgrim Name:</strong> {{ $requestData->pilgrim_name }}</div>
                    <div><strong>Circuit:</strong> {{ $requestData->circuit }}</div>
                    <div><strong>Location:</strong> {{ $requestData->current_location }}</div>
                    <div><strong>Contact:</strong> {{ substr($requestData->contact_number, 0, 4) . '******' . substr($requestData->contact_number, -2) }}</div>
                    <div style="grid-column:1/-1;"><strong>Details:</strong> {{ $requestData->description }}</div>
                    @if($requestData->assigned_to)
                        <div style="grid-column:1/-1; background:#f0fdf4; border:1px solid #bbf7d0; padding:0.5rem 0.75rem; border-radius:6px; color:#166534;">
                            🧑‍🤝‍🧑 <strong>Assigned Coordinator / Sevadar:</strong> {{ $requestData->assigned_to }}
                        </div>
                    @endif
                </div>

                <h3 style="color:#0a192f; font-size:1.15rem; font-weight:800; margin-bottom:1rem;">Update Timeline</h3>
                <div class="route-timeline">
                    @forelse($requestData->updates as $up)
                    <div class="timeline-step">
                        <div class="timeline-dot" style="background:{{ $up->status === 'resolved' ? '#10b981' : ($up->status === 'in_progress' ? '#f59e0b' : '#0284c7') }};"></div>
                        <div class="timeline-content">
                            <div style="display:flex; justify-content:space-between; margin-bottom:0.25rem;">
                                <strong style="color:#0a192f; font-size:0.95rem;">{{ strtoupper($up->status) }}</strong>
                                <span style="font-size:0.75rem; color:#94a3b8;">{{ \Carbon\Carbon::parse($up->created_at)->format('h:i A, d M') }}</span>
                            </div>
                            <p style="font-size:0.88rem; color:#475569; margin:0;">{{ $up->comment }}</p>
                            <span style="font-size:0.75rem; color:#64748b; margin-top:0.35rem; display:block;">Updated by: {{ $up->updated_by }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="color:#64748b; font-size:0.85rem;">Request received. Waiting for ground triage assignment.</div>
                    @endforelse
                </div>
            </div>
        @elseif($code)
            <div style="background:#fee2e2; border:1px solid #fecdd3; border-radius:12px; padding:2rem; text-align:center;">
                <h3 style="color:#991b1b; font-size:1.2rem; font-weight:800; margin-bottom:0.5rem;">No Request Found with Code "{{ $code }}"</h3>
                <p style="color:#7f1d1d; font-size:0.9rem; margin-bottom:1rem;">
                    Please check the code on your SMS or acknowledgement screen. Example format: <code>YS-REQ-202611-1001</code>.
                </p>
            </div>
        @endif
    </div>
</section>
@endsection
