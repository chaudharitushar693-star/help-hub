@extends('layouts.app')

@section('title', 'Smart Search Results – ' . ($q ? $q : 'Yatri Sahayata'))

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container">
        <!-- Search Bar Header -->
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem; margin-bottom:2.5rem;">
            <form action="{{ route('search') }}" method="GET">
                <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                    <div style="flex:1; position:relative; min-width:260px;">
                        <input type="text" name="q" class="form-control" placeholder="Search facilities, clinics, dharamshalas, FAQs..." value="{{ $q }}" required>
                    </div>
                    <select name="category" class="form-control" style="width:auto;">
                        <option value="all" {{ $category === 'all' ? 'selected' : '' }}>All Directories</option>
                        <option value="facilities" {{ $category === 'facilities' ? 'selected' : '' }}>Facilities Only</option>
                        <option value="medical" {{ $category === 'medical' ? 'selected' : '' }}>Medical Centres</option>
                        <option value="accommodations" {{ $category === 'accommodations' ? 'selected' : '' }}>Accommodations</option>
                        <option value="faqs" {{ $category === 'faqs' ? 'selected' : '' }}>FAQs Only</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Search Platform</button>
                </div>
            </form>
        </div>

        <div style="margin-bottom:1.5rem;">
            <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">
                @if($q)
                    Results for "<span style="color:#f59e0b;">{{ $q }}</span>" ({{ $totalCount }} items found)
                @else
                    Search Directory
                @endif
            </h1>
        </div>

        @if($totalCount === 0)
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:3.5rem 1.5rem; text-align:center;">
                <div style="font-size:3rem; margin-bottom:1rem;">🔍</div>
                <h3 style="color:#0a192f; font-size:1.35rem; font-weight:800; margin-bottom:0.5rem;">No direct verified matches found</h3>
                <p style="color:#64748b; max-width:540px; margin:0 auto 1.5rem; font-size:0.95rem;">
                    We could not find records matching "{{ $q }}". Try searching for generalized pilgrim keywords like <strong>Katra, Battery Car, Clinic, Cloak Room, Blanket</strong> or browse our categorized directories below.
                </p>
                <div style="display:flex; justify-content:center; gap:0.75rem; flex-wrap:wrap;">
                    <a href="{{ route('routes') }}" class="btn btn-secondary">Browse Routes & Map</a>
                    <a href="{{ route('medical') }}" class="btn btn-secondary">Medical Emergency Directory</a>
                    <a href="{{ route('accommodation') }}" class="btn btn-secondary">Dharamshalas & Stay</a>
                    <a href="{{ route('faqs') }}" class="btn btn-primary">Browse All FAQs</a>
                </div>
            </div>
        @else
            <!-- Categorized Results Sections -->
            @if($results['medical']->count() > 0)
                <h3 style="color:#991b1b; font-size:1.25rem; font-weight:800; margin-top:2rem; margin-bottom:1rem;">🏥 Medical Centres & First Aid Posts ({{ $results['medical']->count() }})</h3>
                <div class="cards-grid" style="margin-bottom:2rem;">
                    @foreach($results['medical'] as $m)
                    <div class="yatri-card" style="border-top:3px solid #ef4444;">
                        <span class="card-badge badge-emergency">{{ $m->centre_type }}</span>
                        <h4 class="card-title">{{ $m->name }}</h4>
                        <div class="card-meta">
                            <div>📍 {{ $m->location }} (<strong>{{ $m->circuit }}</strong>)</div>
                            <div>📞 Helpline: <strong style="color:#b91c1c;">{{ $m->helpline_number }}</strong></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            @if($results['facilities']->count() > 0)
                <h3 style="color:#0a192f; font-size:1.25rem; font-weight:800; margin-top:2rem; margin-bottom:1rem;">ℹ️ Rest Camps, Cloak Rooms & Facilitation Desks ({{ $results['facilities']->count() }})</h3>
                <div class="cards-grid" style="margin-bottom:2rem;">
                    @foreach($results['facilities'] as $f)
                    <div class="yatri-card">
                        <span class="card-badge badge-info">{{ $f->category }}</span>
                        <h4 class="card-title">{{ $f->name }}</h4>
                        <div class="card-meta">
                            <div>📍 {{ $f->location }} (<strong>{{ $f->circuit }}</strong>)</div>
                            <div>⏱️ Hours: {{ $f->operating_hours }}</div>
                        </div>
                        <p class="card-desc">{{ $f->description }}</p>
                    </div>
                    @endforeach
                </div>
            @endif

            @if($results['accommodations']->count() > 0)
                <h3 style="color:#0a192f; font-size:1.25rem; font-weight:800; margin-top:2rem; margin-bottom:1rem;">🏨 Verified Dharamshalas & Stay ({{ $results['accommodations']->count() }})</h3>
                <div class="cards-grid" style="margin-bottom:2rem;">
                    @foreach($results['accommodations'] as $a)
                    <div class="yatri-card">
                        <span class="card-badge badge-gold">⭐ {{ $a->rating }} ({{ $a->type }})</span>
                        <h4 class="card-title">{{ $a->name }}</h4>
                        <div class="card-meta">
                            <div>📍 {{ $a->address }} (<strong>{{ $a->circuit }}</strong>)</div>
                            <div>💰 Tariff: {{ $a->price_range }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            @if($results['faqs']->count() > 0)
                <h3 style="color:#0a192f; font-size:1.25rem; font-weight:800; margin-top:2rem; margin-bottom:1rem;">❓ Questions & Practical Answers ({{ $results['faqs']->count() }})</h3>
                <div class="faq-wrap" style="margin-bottom:2rem;">
                    @foreach($results['faqs'] as $faq)
                    <div class="faq-item">
                        <div style="padding:1.25rem;">
                            <strong style="color:#0a192f; font-size:1.05rem; display:block; margin-bottom:0.5rem;">Q: {{ $faq->question }}</strong>
                            <p style="color:#475569; font-size:0.92rem; margin:0;">{{ $faq->answer }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
