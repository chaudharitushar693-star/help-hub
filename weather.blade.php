@extends('layouts.app')

@section('title', 'Pilgrim Track Weather & Microclimate Forecasts')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">High-Altitude Meteorological Bulletin</span>
            <h1 class="section-title">Microclimate Advisories & Track Conditions</h1>
            <p class="section-desc">Mountainous tracks witness rapid temperature drops after sunset. Review hourly forecasts before beginning ascent.</p>
        </div>

        <div class="weather-card" style="margin-bottom:2.5rem;">
            <div class="weather-header">
                <div>
                    <span class="card-badge badge-gold">Active Bulletin: {{ $circuit }}</span>
                    <h2 style="color:#ffffff; font-size:1.8rem; font-weight:800; margin:0.4rem 0;">Trikuta Hills / Himalayan Ridge</h2>
                    <p style="color:#cbd5e1; font-size:0.9rem; margin:0;">Light mountain breeze, partly cloudy with isolated drizzle likelihood.</p>
                </div>
                <div style="text-align:right;">
                    <div class="weather-temp">16°C</div>
                    <div style="color:#fbbf24; font-size:0.85rem; font-weight:600;">Feels like 14°C (Night Wind)</div>
                </div>
            </div>

            <div class="weather-grid">
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Morning (06:00 - 11:00)</div>
                    <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">19°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">Clear & crisp. Best window for steep ascent.</div>
                </div>
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Afternoon (12:00 - 16:00)</div>
                    <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">22°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">Warm sunlight. Carry hydration bottles.</div>
                </div>
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Evening (17:00 - 21:00)</div>
                    <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">14°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">Chilly mountain drafts. Wear thermal layer.</div>
                </div>
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Night (22:00 - 05:00)</div>
                    <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">9°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">Cold winds at Bhairon peak. Heavy woolens mandatory.</div>
                </div>
            </div>
        </div>

        <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:1rem;">Essential Weather Safety Rules</h3>
        <div class="cards-grid">
            <div class="yatri-card">
                <span class="card-badge badge-emergency">Hypothermia Prevention</span>
                <h4 style="font-size:1.05rem; color:#0a192f; margin-bottom:0.4rem;">Layered Clothing Strategy</h4>
                <p style="font-size:0.88rem; color:#64748b;">Do not wear a single heavy cotton jacket. Use a moisture-wicking inner tee, fleece sweater, and windproof outer layer.</p>
            </div>
            <div class="yatri-card">
                <span class="card-badge badge-info">Rain & Slippery Tracks</span>
                <h4 style="font-size:1.05rem; color:#0a192f; margin-bottom:0.4rem;">Waterproof Poncho & Shoes</h4>
                <p style="font-size:0.88rem; color:#64748b;">Smooth marble tiles near Bhawan can become slick in rain. Wear rubber-grip athletic shoes or use rubber-tipped walking sticks.</p>
            </div>
            <div class="yatri-card">
                <span class="card-badge badge-gold">High Altitude Acclimatization</span>
                <h4 style="font-size:1.05rem; color:#0a192f; margin-bottom:0.4rem;">Avoid Fast Climbing</h4>
                <p style="font-size:0.88rem; color:#64748b;">Keep your pace conversational. If your pulse exceeds 120 bpm at rest or lips turn bluish, visit the nearest oxygen parlor.</p>
            </div>
        </div>
    </div>
</section>
@endsection
