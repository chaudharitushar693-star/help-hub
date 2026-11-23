@extends('layouts.app')

@section('title', 'Verified Accommodations & Dharamshalas – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
            <div>
                <span class="section-tag">Economical & Trust Accommodations</span>
                <h1 class="section-title" style="margin-bottom:0.25rem;">Verified Pilgrim Accommodations</h1>
                <p style="color:#64748b; margin:0;">Trust dharamshalas, shrine guest houses, and registered family rest shelters.</p>
            </div>

            <!-- Filters -->
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('accommodation') }}" class="btn btn-sm btn-secondary">All</a>
                <a href="{{ route('accommodation') }}?circuit=Vaishno+Devi" class="btn btn-sm btn-secondary">Vaishno Devi</a>
                <a href="{{ route('accommodation') }}?circuit=Ujjain+Mahakal" class="btn btn-sm btn-secondary">Ujjain Mahakal</a>
                <a href="{{ route('accommodation') }}?circuit=Char+Dham" class="btn btn-sm btn-secondary">Char Dham</a>
                <a href="{{ route('accommodation') }}?circuit=Omkareshwar" class="btn btn-sm btn-secondary">Omkareshwar</a>
            </div>
        </div>

        <div class="cards-grid">
            @forelse($accommodations as $acc)
            <div class="yatri-card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <span class="card-badge badge-gold">⭐ {{ $acc->rating }} / 5.0</span>
                    <span class="card-badge badge-info">{{ $acc->type }}</span>
                </div>
                <h3 class="card-title">{{ $acc->name }}</h3>
                <div class="card-meta">
                    <div class="card-meta-item">📍 {{ $acc->address }} (<strong>{{ $acc->circuit }}</strong>)</div>
                    <div class="card-meta-item">💰 Tariff: <strong>{{ $acc->price_range }}</strong></div>
                    <div class="card-meta-item">📞 Contact: <strong>{{ $acc->contact_phone }}</strong></div>
                </div>
                <p class="card-desc"><strong>Amenities:</strong> {{ $acc->amenities }}</p>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#10b981; font-weight:700;">✓ Shrine Board Approved</span>
                    @if($acc->booking_url)
                        <a href="{{ $acc->booking_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">Official Booking Link &rarr;</a>
                    @else
                        <a href="tel:{{ $acc->contact_phone }}" class="btn btn-sm btn-navy">Call Desk</a>
                    @endif
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1; padding:3rem; text-align:center; color:#64748b; background:#f8fafc; border-radius:12px;">
                No accommodations found for this filter.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
