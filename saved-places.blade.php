@extends('layouts.app')

@section('title', 'My Saved Places & Facilities – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:2.5rem 0;">
    <div class="container" style="max-width:960px;">
        <div style="margin-bottom:2rem;">
            <span class="section-tag">Personal Bookmarks</span>
            <h1 class="section-title" style="margin-bottom:0.25rem;">My Saved Places & Facilities</h1>
            <p style="color:#64748b; margin:0;">Quick reference bookmarked rest sheds, cloakrooms, and verified dharamshalas.</p>
        </div>

        <div class="cards-grid">
            @forelse($places as $p)
            <div class="yatri-card">
                <span class="card-badge badge-info">{{ ucfirst($p->place_type) }}</span>
                <h3 class="card-title">{{ $p->place_name }}</h3>
                <div class="card-meta">
                    <div>📍 Circuit: <strong>{{ $p->circuit }}</strong></div>
                    <div>Saved on: {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('routes') }}?circuit={{ urlencode($p->circuit) }}" class="btn btn-sm btn-navy">Open on Map</a>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1; padding:3.5rem; text-align:center; color:#64748b; background:#ffffff; border:1px solid #e2e8f0; border-radius:12px;">
                <div style="font-size:2.5rem; margin-bottom:0.75rem;">⭐</div>
                <h3 style="color:#0a192f; font-size:1.2rem; font-weight:700;">No Saved Places Yet</h3>
                <p style="font-size:0.88rem; color:#64748b; margin-top:0.25rem;">
                    Browse facilities, dharamshalas, and medical centers to bookmark them for offline reference.
                </p>
                <a href="{{ route('routes') }}" class="btn btn-primary" style="margin-top:1rem;">Explore Routes & Facilities</a>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
