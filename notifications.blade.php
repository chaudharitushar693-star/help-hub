@extends('layouts.app')

@section('title', 'Pilgrim Notifications & Bulletins – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:2.5rem 0;">
    <div class="container" style="max-width:800px;">
        <div style="margin-bottom:2rem;">
            <span class="section-tag">Alerts & Directives</span>
            <h1 class="section-title" style="margin-bottom:0.25rem;">Notifications & Operational Messages</h1>
            <p style="color:#64748b; margin:0;">Real-time notifications regarding your yatra requests and track alerts.</p>
        </div>

        <div style="display:flex; flex-direction:column; gap:1rem;">
            @forelse($notifications as $n)
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-left:4px solid {{ $n->type === 'warning' ? '#f59e0b' : ($n->type === 'success' ? '#10b981' : '#0284c7') }}; border-radius:10px; padding:1.25rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.25rem;">
                    <strong style="color:#0a192f; font-size:1.05rem;">{{ $n->title }}</strong>
                    <span style="font-size:0.75rem; color:#94a3b8;">{{ \Carbon\Carbon::parse($n->created_at)->format('d M, h:i A') }}</span>
                </div>
                <p style="font-size:0.9rem; color:#475569; margin:0.25rem 0 0.5rem;">{{ $n->message }}</p>
                @if($n->action_url)
                    <a href="{{ $n->action_url }}" style="font-size:0.8rem; color:#f59e0b; font-weight:600; text-decoration:none;">View Details &rarr;</a>
                @endif
            </div>
            @empty
            <div style="padding:3rem; text-align:center; color:#64748b; background:#ffffff; border:1px solid #e2e8f0; border-radius:12px;">
                No unread notifications.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
