@extends('layouts.app')

@section('title', 'My Assistance Requests & Tracking Timeline – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:2.5rem 0;">
    <div class="container" style="max-width:960px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
            <div>
                <span class="section-tag">Request Tracking & Triage</span>
                <h1 class="section-title" style="margin-bottom:0.25rem;">My Assistance Requests</h1>
                <p style="color:#64748b; margin:0;">Track real-time responses from volunteer coordinators and ground support teams.</p>
            </div>
            <button onclick="document.getElementById('openSosBtn').click()" class="btn btn-sos">🚨 New Emergency Request</button>
        </div>

        <div style="display:flex; flex-direction:column; gap:1.5rem;">
            @forelse($requests as $req)
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.75rem; box-shadow:var(--shadow-sm);">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem; border-bottom:1px solid #f1f5f9; padding-bottom:1rem;">
                    <div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <h3 style="color:#0a192f; font-size:1.25rem; font-weight:800; margin:0;">{{ $req->tracking_code }}</h3>
                            <span class="card-badge {{ $req->priority === 'critical' ? 'badge-emergency' : ($req->priority === 'high' ? 'badge-gold' : 'badge-info') }}" style="margin:0;">
                                {{ strtoupper($req->priority) }} PRIORITY
                            </span>
                        </div>
                        <div style="font-size:0.85rem; color:#64748b; margin-top:0.25rem;">
                            Circuit: <strong>{{ $req->circuit }}</strong> | Logged: {{ \Carbon\Carbon::parse($req->created_at)->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <div>
                        <span class="card-badge {{ $req->status === 'resolved' ? 'badge-verified' : ($req->status === 'in_progress' ? 'badge-gold' : 'badge-emergency') }}" style="font-size:0.85rem; padding:0.35rem 0.85rem; margin:0;">
                            STATUS: {{ strtoupper($req->status) }}
                        </span>
                    </div>
                </div>

                <div style="font-size:0.92rem; color:#334155; margin-bottom:1.25rem; line-height:1.5;">
                    <div><strong>Location:</strong> {{ $req->current_location }}</div>
                    <div><strong>Request Details:</strong> {{ $req->description }}</div>
                    @if($req->assigned_to)
                        <div style="margin-top:0.5rem; color:#166534; font-weight:600;">
                            🧑‍🤝‍🧑 Assigned Coordinator: {{ $req->assigned_to }}
                        </div>
                    @endif
                </div>

                <!-- Timeline Updates -->
                <h4 style="font-size:0.95rem; font-weight:700; color:#0a192f; margin-bottom:0.75rem;">Timeline Updates:</h4>
                <div class="route-timeline" style="margin-left:0.5rem;">
                    @foreach($req->updates as $up)
                    <div class="timeline-step">
                        <div class="timeline-dot" style="background:{{ $up->status === 'resolved' ? '#10b981' : ($up->status === 'in_progress' ? '#f59e0b' : '#0284c7') }};"></div>
                        <div class="timeline-content" style="padding:0.75rem 1rem;">
                            <div style="display:flex; justify-content:space-between;">
                                <strong style="font-size:0.88rem; color:#0a192f;">{{ strtoupper($up->status) }}</strong>
                                <span style="font-size:0.72rem; color:#94a3b8;">{{ \Carbon\Carbon::parse($up->created_at)->format('h:i A, d M') }}</span>
                            </div>
                            <p style="font-size:0.85rem; color:#475569; margin:0.25rem 0 0;">{{ $up->comment }}</p>
                            <span style="font-size:0.72rem; color:#64748b;">By: {{ $up->updated_by }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div style="padding:3.5rem; text-align:center; color:#64748b; background:#ffffff; border:1px solid #e2e8f0; border-radius:12px;">
                <div style="font-size:2.5rem; margin-bottom:0.75rem;">🛡️</div>
                <h3 style="color:#0a192f; font-size:1.2rem; font-weight:700;">No Active Assistance Cases</h3>
                <p style="font-size:0.88rem; color:#64748b; margin-top:0.25rem;">
                    If you require medical aid, wheelchair accompaniment, or reporting a lost item, use the buttons above.
                </p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
