@extends('layouts.app')

@section('title', 'Official Advisories & Operational Notices – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:960px;">
        <span class="section-tag">Ground Bulletins</span>
        <h1 class="section-title">Live Safety Advisories & Operational Notices</h1>
        <p class="section-desc" style="margin-bottom:2rem;">Official updates compiled from temple trusts, district magistrates, and ground sevadar units.</p>

        <div style="display:flex; flex-direction:column; gap:1.25rem;">
            @forelse($announcements as $ann)
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-left:5px solid {{ $ann->priority === 'alert' ? '#ef4444' : ($ann->priority === 'advisory' ? '#f59e0b' : '#0284c7') }}; border-radius:10px; padding:1.5rem; box-shadow:var(--shadow-sm);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem; flex-wrap:wrap; gap:0.5rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span class="card-badge {{ $ann->priority === 'alert' ? 'badge-emergency' : ($ann->priority === 'advisory' ? 'badge-gold' : 'badge-info') }}" style="margin:0;">
                            {{ strtoupper($ann->priority) }}
                        </span>
                        <strong style="color:#0a192f; font-size:1.15rem;">{{ $ann->title }}</strong>
                    </div>
                    <span style="font-size:0.75rem; color:#64748b;">
                        Published: {{ \Carbon\Carbon::parse($ann->published_at)->format('d M Y, h:i A') }}
                    </span>
                </div>
                <div style="font-size:0.85rem; color:#64748b; margin-bottom:0.75rem;">
                    Circuit: <strong style="color:#0a192f;">{{ $ann->circuit }}</strong>
                </div>
                <p style="font-size:0.95rem; color:#334155; line-height:1.6; margin:0;">
                    {{ $ann->content }}
                </p>
            </div>
            @empty
            <div style="padding:3rem; text-align:center; color:#64748b; background:#f8fafc; border-radius:12px;">
                No active critical advisories. All routes operating under normal protocol.
            </div>
            @endforelse
        </div>

        <div style="margin-top:2rem;">
            {{ $announcements->links() }}
        </div>
    </div>
</section>
@endsection
