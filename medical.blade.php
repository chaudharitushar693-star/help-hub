@extends('layouts.app')

@section('title', 'Emergency Medical Assistance Directory – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container">
        <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:12px; padding:1.5rem; margin-bottom:2.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1.25rem;">
            <div>
                <span class="card-badge badge-emergency">🚨 CRITICAL ADVISORY</span>
                <h2 style="color:#991b1b; font-size:1.6rem; font-weight:800; margin:0.25rem 0;">24x7 High-Altitude Medical Emergency Triage</h2>
                <p style="color:#9f1239; margin:0; font-size:0.95rem;">
                    If experiencing chest tightness, acute breathlessness, or hypothermia, stop moving immediately and contact the nearest emergency unit below.
                </p>
            </div>
            <div style="display:flex; gap:0.75rem;">
                <a href="tel:112" class="btn btn-sos" style="font-size:1rem; padding:0.75rem 1.5rem;">📞 Dial 112</a>
                <a href="tel:108" class="btn btn-navy" style="font-size:1rem; padding:0.75rem 1.5rem;">🚑 Dial 108</a>
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
            <h2 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">Verified Emergency Posts & Dispensaries</h2>
            <div style="display:flex; gap:0.5rem;">
                <a href="{{ route('medical') }}" class="btn btn-sm btn-secondary">All Circuits</a>
                <a href="{{ route('medical') }}?circuit=Vaishno+Devi" class="btn btn-sm btn-secondary">Vaishno Devi</a>
                <a href="{{ route('medical') }}?circuit=Ujjain+Mahakal" class="btn btn-sm btn-secondary">Ujjain Mahakal</a>
                <a href="{{ route('medical') }}?circuit=Char+Dham" class="btn btn-sm btn-secondary">Char Dham</a>
            </div>
        </div>

        <div class="cards-grid">
            @forelse($medicalCentres as $m)
            <div class="yatri-card" style="border-top:4px solid #ef4444;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <span class="card-badge badge-emergency">{{ $m->centre_type }}</span>
                    <span style="font-size:0.75rem; background:#fee2e2; color:#b91c1c; font-weight:700; padding:2px 8px; border-radius:12px;">
                        {{ $m->is_24x7 ? '24x7 OPEN' : 'REGULAR' }}
                    </span>
                </div>
                <h3 class="card-title" style="color:#991b1b; margin-top:0.4rem;">{{ $m->name }}</h3>
                <div class="card-meta">
                    <div class="card-meta-item">📍 {{ $m->location }} (<strong>{{ $m->circuit }}</strong>)</div>
                    <div class="card-meta-item">👨‍⚕️ In-Charge: <strong>{{ $m->doctor_in_charge ?? 'Chief Medical Officer' }}</strong></div>
                    <div class="card-meta-item">🩺 Equipment: {{ $m->available_facilities }}</div>
                </div>

                <div style="background:#fef2f2; border:1px solid #fee2e2; border-radius:8px; padding:0.85rem; margin-top:auto; margin-bottom:1rem; text-align:center;">
                    <span style="font-size:0.75rem; color:#64748b; display:block;">EMERGENCY HELPLINE</span>
                    <a href="tel:{{ $m->helpline_number }}" style="font-size:1.25rem; font-weight:800; color:#b91c1c; text-decoration:none;">
                        📞 {{ $m->helpline_number }}
                    </a>
                </div>

                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#10b981; font-weight:700;">✓ Ground Verified</span>
                    <a href="{{ route('routes') }}?circuit={{ urlencode($m->circuit) }}" class="btn btn-sm btn-navy">Locate on Map</a>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1; padding:3rem; text-align:center; color:#64748b; background:#f8fafc; border-radius:12px;">
                No specific medical posts found matching your filter. Please call emergency hotline <strong>112</strong> or <strong>108</strong>.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
