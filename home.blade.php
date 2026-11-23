@extends('layouts.app')

@section('title', 'YATRI SAHAYATA – Smart Pilgrim Assistance Platform | Your Journey, Our Responsibility')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-grid">
        <div>
            <div class="hero-tag">
                <span>🛡️</span> Verified Smart Pilgrim Guidance
            </div>
            <h1 class="hero-title">
                Your Sacred Journey,<br>
                <span class="gold-text">Our Responsibility.</span>
            </h1>
            <p class="hero-lead">
                Reliable route tracking, emergency first aid posts, RFID registration guidelines, wheelchair assistance, and intelligent itinerary planning for millions of sacred yatris across India.
            </p>

            <!-- Hero Action Buttons -->
            <div style="display:flex; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
                <a href="{{ route('planner.index') }}" class="btn btn-primary" style="font-size:1.05rem; padding:0.85rem 1.75rem;">
                    <span>✨</span> Plan Your Yatra Now
                </a>
                <a href="{{ route('routes') }}" class="btn btn-secondary" style="font-size:1.05rem; padding:0.85rem 1.5rem;">
                    <span>🗺️</span> View Interactive Route Map
                </a>
            </div>

            <!-- Quick Stat Counters -->
            <div class="hero-stats">
                <div class="stat-item">
                    <h3>24x7</h3>
                    <p>Emergency Medical & First Aid Directory</p>
                </div>
                <div class="stat-item">
                    <h3>100% Free</h3>
                    <p>RFID Yatra Slip & Locker Information</p>
                </div>
                <div class="stat-item">
                    <h3>7+</h3>
                    <p>Major Pilgrimage Circuits Covered</p>
                </div>
            </div>
        </div>

        <!-- Smart Search Card with Autocomplete -->
        <div>
            <div class="search-card">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                    <h3 style="font-size:1.15rem; font-weight:800; color:#0a192f; margin:0;">🔍 Smart Pilgrim Search</h3>
                    <span style="font-size:0.75rem; background:#f1f5f9; padding:2px 8px; border-radius:12px; color:#475569;">Live Database</span>
                </div>

                <form action="{{ route('search') }}" method="GET">
                    <div class="search-input-wrap">
                        <span class="search-icon">🔎</span>
                        <input type="text" id="mainSearchInput" name="q" class="search-input" placeholder="Search facilities, clinics, dharamshalas..." autocomplete="off">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>

                    <!-- Autocomplete Dropdown Container -->
                    <div id="searchAutocomplete" class="autocomplete-results"></div>
                </form>

                <!-- Quick Circuit Filter Pills -->
                <div style="margin-top:1.25rem;">
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Popular Holy Circuits:</span>
                    <div class="circuit-pills">
                        <a href="{{ route('routes') }}?circuit=Vaishno+Devi" class="circuit-pill">🚩 Vaishno Devi</a>
                        <a href="{{ route('routes') }}?circuit=Ujjain+Mahakal" class="circuit-pill">🔱 Ujjain Mahakal</a>
                        <a href="{{ route('routes') }}?circuit=Char+Dham" class="circuit-pill">🏔️ Char Dham</a>
                        <a href="{{ route('routes') }}?circuit=Omkareshwar" class="circuit-pill">🌊 Omkareshwar</a>
                        <a href="{{ route('routes') }}?circuit=Varanasi+Kashi" class="circuit-pill">🪔 Kashi Vishwanath</a>
                    </div>
                </div>

                <!-- Emergency SOS Quick Strip -->
                <div style="margin-top:1.5rem; background:#fee2e2; border:1px solid #fecdd3; border-radius:8px; padding:0.85rem; display:flex; align-items:center; justify-content:space-between;">
                    <div style="font-size:0.82rem; color:#991b1b;">
                        <strong>Need Ground Help?</strong> Track code status or raise alert.
                    </div>
                    <a href="{{ route('assistance.track') }}" class="btn btn-sm btn-navy" style="font-size:0.75rem;">Track Request</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Live Safety Advisories Strip -->
@if(isset($announcements) && $announcements->count() > 0)
<section style="background:#0f172a; color:#fff; padding:1.25rem 0; border-bottom:1px solid rgba(255,255,255,0.1);">
    <div class="container" style="display:flex; align-items:center; gap:1.25rem;">
        <span class="card-badge badge-emergency" style="margin:0; font-size:0.75rem; white-space:nowrap;">🚨 LIVE ADVISORY</span>
        <div style="flex:1; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; font-size:0.9rem; color:#cbd5e1;">
            @foreach($announcements as $ann)
                <span style="margin-right:2rem;">
                    <strong style="color:#fbbf24;">[{{ $ann->circuit }}]</strong> {{ $ann->title }} — {{ Str::limit($ann->content, 90) }}
                </span>
            @endforeach
        </div>
        <a href="{{ route('announcements') }}" style="color:#fbbf24; font-size:0.8125rem; font-weight:600; text-decoration:underline; white-space:nowrap;">View All</a>
    </div>
</section>
@endif

<!-- Route Guide & Interactive Map Preview Section -->
<section class="section" style="background:#ffffff;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Interactive Route Overview</span>
            <h2 class="section-title">Verified Tracks & Milepost Guidance</h2>
            <p class="section-desc">Accurate geographical locations for base camps, steep climbs, medical dispensaries, and rest stops across sacred pilgrimage paths.</p>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:center;">
            <!-- Map Container -->
            <div style="border-radius:var(--radius-lg); overflow:hidden; border:1px solid var(--border-subtle); box-shadow:var(--shadow-md);">
                <div id="yatriMap" style="height:420px; width:100%;"></div>
            </div>

            <!-- Route Steps Timeline -->
            <div>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                    <h3 style="color:#0a192f; font-size:1.35rem; font-weight:800; margin:0;">Mata Vaishno Devi Trek (13.5 KM)</h3>
                    <span class="card-badge badge-verified">Elevation 5,200 ft</span>
                </div>

                <div class="route-timeline">
                    <div class="timeline-step">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong style="color:#0a192f;">1. Katra Base Camp (KM 0)</strong>
                            <p style="font-size:0.85rem; color:#64748b; margin-top:2px;">RFID Yatra Slip Counters, luggage cloakrooms, prepaid auto stands.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong style="color:#0a192f;">2. Banganga Checkpost (KM 1.5)</strong>
                            <p style="font-size:0.85rem; color:#64748b; margin-top:2px;">Security screening, holy bath ghat, free drinking water refill, 24x7 dispensary.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong style="color:#0a192f;">3. Ardhkuwari Midpoint (KM 6.0)</strong>
                            <p style="font-size:0.85rem; color:#64748b; margin-top:2px;">Garbhjun Cave, massive resting sheds, subsidized blankets, oxygen booth.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong style="color:#0a192f;">4. Sanjichhat Helipad (KM 9.5)</strong>
                            <p style="font-size:0.85rem; color:#64748b; margin-top:2px;">Battery car terminal for senior citizens, helicopter helipad, critical cardiac post.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong style="color:#0a192f;">5. Sacred Bhawan Complex (KM 13.5)</strong>
                            <p style="font-size:0.85rem; color:#64748b; margin-top:2px;">Holy sanctum cave, free cloakrooms, ropeway cable car to Bhairon Temple.</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top:1.5rem; text-align:right;">
                    <a href="{{ route('routes') }}" class="btn btn-navy">Open Full Route Map & Altitude Charts &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Verified Facilities Section -->
<section class="section" style="background:#f8fafc;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Ground Support Infrastructure</span>
            <h2 class="section-title">Verified Rest Sheds & Cloak Rooms</h2>
            <p class="section-desc">Convenient, trusted stations to store luggage, acquire biometric passes, and rest with elders.</p>
        </div>

        <div class="cards-grid">
            @foreach($facilities as $f)
            <div class="yatri-card">
                <span class="card-badge badge-info">{{ $f->category }}</span>
                <h3 class="card-title">{{ $f->name }}</h3>
                <div class="card-meta">
                    <div class="card-meta-item">📍 {{ $f->location }} (<strong>{{ $f->circuit }}</strong>)</div>
                    <div class="card-meta-item">⏱️ Operating Hours: <strong>{{ $f->operating_hours }}</strong></div>
                    <div class="card-meta-item">💰 Fee Structure: <strong>{{ $f->fee_type }}</strong></div>
                </div>
                <p class="card-desc">{{ $f->description }}</p>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#10b981; font-weight:700;">✓ Shrine Verified</span>
                    <a href="{{ route('routes') }}?circuit={{ urlencode($f->circuit) }}" class="btn btn-sm btn-secondary">Locate on Map</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Emergency Medical Assistance Section -->
<section class="section" style="background:#ffffff;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag" style="color:#dc2626;">Immediate Healthcare Triage</span>
            <h2 class="section-title">Emergency Medical & First Aid Posts</h2>
            <p class="section-desc">Critical cardiac centers, oxygen concentrator booths, and 24x7 ambulance posts ready along the pilgrimage track.</p>
        </div>

        <div class="cards-grid">
            @foreach($medicalCentres as $m)
            <div class="yatri-card" style="border-top:4px solid #ef4444;">
                <span class="card-badge badge-emergency">🏥 {{ $m->centre_type }}</span>
                <h3 class="card-title" style="color:#991b1b;">{{ $m->name }}</h3>
                <div class="card-meta">
                    <div class="card-meta-item">📍 {{ $m->location }}</div>
                    <div class="card-meta-item">👨‍⚕️ In-Charge: {{ $m->doctor_in_charge ?? 'Duty Medical Officer' }}</div>
                    <div class="card-meta-item">🩺 Facilities: {{ $m->available_facilities }}</div>
                </div>
                <div style="background:#fef2f2; border:1px solid #fee2e2; border-radius:6px; padding:0.65rem 0.85rem; margin-bottom:1rem; font-size:0.92rem; font-weight:700; color:#b91c1c;">
                    📞 Direct Emergency Hotline: <a href="tel:{{ $m->helpline_number }}" style="color:#b91c1c; text-decoration:underline;">{{ $m->helpline_number }}</a>
                </div>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#b91c1c; font-weight:700;">{{ $m->is_24x7 ? '🚨 24x7 Operational' : 'Open Regular Hours' }}</span>
                    <a href="{{ route('medical') }}" class="btn btn-sm btn-navy">View Directory</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Weather Advisory & High-Altitude Packing Widget -->
<section class="section" style="background:#0a192f; color:#ffffff;">
    <div class="container">
        <div class="weather-card">
            <div class="weather-header">
                <div>
                    <span class="section-tag" style="color:#fbbf24;">Microclimate Advisory</span>
                    <h2 style="font-size:2rem; font-weight:800; color:#ffffff; margin:0.25rem 0;">Trikuta Hills & Jammu Circuit Weather</h2>
                    <p style="color:#cbd5e1; font-size:0.9rem; margin-top:0.25rem;">Live tracking for altitude variations between Katra Base (850m) and Holy Bhawan (1585m).</p>
                </div>
                <div style="text-align:right;">
                    <div class="weather-temp">16°C</div>
                    <div style="color:#cbd5e1; font-size:0.9rem;">Partly Cloudy / Chilly Evening</div>
                </div>
            </div>

            <div class="weather-grid">
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Katra Base (850m)</div>
                    <div style="font-size:1.4rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">21°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">Mild & pleasant walking condition.</div>
                </div>
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Ardhkuwari (1400m)</div>
                    <div style="font-size:1.4rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">16°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">Breezy; light thermal jacket advised.</div>
                </div>
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Bhawan (1585m)</div>
                    <div style="font-size:1.4rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">12°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">Cold mountain winds after sunset.</div>
                </div>
                <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                    <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Bhairon Temple (2010m)</div>
                    <div style="font-size:1.4rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">9°C</div>
                    <div style="font-size:0.8rem; color:#cbd5e1;">High wind chill. Heavy woolens required.</div>
                </div>
            </div>

            <div style="margin-top:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                <span style="font-size:0.85rem; color:#e2e8f0;">💡 Rain poncho and walking stick recommended before commencing climb.</span>
                <a href="{{ route('weather') }}" class="btn btn-outline-gold btn-sm">View Complete Multi-Circuit Weather &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Verified Accommodations & Dharamshalas Preview -->
<section class="section" style="background:#f8fafc;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Economical & Trust Accommodations</span>
            <h2 class="section-title">Verified Dharamshalas & Pilgrim Lodges</h2>
            <p class="section-desc">Clean, pure-vegetarian guest houses with hot water, blanket stores, and elderly assistance.</p>
        </div>

        <div class="cards-grid">
            @foreach($accommodations as $acc)
            <div class="yatri-card">
                <span class="card-badge badge-gold">⭐ {{ $acc->rating }} / 5.0 Rating</span>
                <h3 class="card-title">{{ $acc->name }}</h3>
                <div class="card-meta">
                    <div class="card-meta-item">📍 {{ $acc->address }} (<strong>{{ $acc->circuit }}</strong>)</div>
                    <div class="card-meta-item">💰 Tariff: <strong>{{ $acc->price_range }}</strong></div>
                    <div class="card-meta-item">📞 Contact: <strong>{{ $acc->contact_phone }}</strong></div>
                </div>
                <p class="card-desc"><strong>Amenities:</strong> {{ $acc->amenities }}</p>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#475569;">Verified Trust</span>
                    <a href="{{ $acc->booking_url ?? route('accommodation') }}" target="_blank" class="btn btn-sm btn-primary">Book / Inquire</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Frequently Asked Questions (Bilingual Accordion) -->
<section class="section" style="background:#ffffff;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Essential Yatra Knowledge</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-desc">Clear answers in English and Hindi regarding RFID slips, battery cars, cloakrooms, and medical support.</p>
        </div>

        <div class="faq-wrap">
            @foreach($faqs as $faq)
            <div class="faq-item">
                <button type="button" class="faq-question">
                    <span>{{ $faq->question }}</span>
                    <span class="toggle-icon">+</span>
                </button>
                <div class="faq-answer" style="display:none;">
                    <p>{{ $faq->answer }}</p>
                    @if($faq->hindi_answer)
                    <div class="faq-hindi">
                        <strong>हिन्दी अनुवाद:</strong><br>
                        {{ $faq->hindi_answer }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:2rem;">
            <a href="{{ route('faqs') }}" class="btn btn-secondary">View All FAQs & Guidelines &rarr;</a>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section style="background:linear-gradient(135deg, #f59e0b, #d97706); color:#0f172a; padding:3.5rem 0;">
    <div class="container" style="text-align:center; max-width:760px;">
        <h2 style="font-size:2.25rem; font-weight:800; margin-bottom:1rem;">Ready to Plan Your Sacred Yatra?</h2>
        <p style="font-size:1.1rem; margin-bottom:2rem; color:#1e293b;">
            Generate a personalized, rule-based itinerary configured for your group size, elderly parents, and travel budget in under 30 seconds.
        </p>
        <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
            <a href="{{ route('planner.index') }}" class="btn btn-navy" style="font-size:1.05rem; padding:0.85rem 2rem;">Plan My Itinerary Now</a>
            <a href="{{ route('checklist') }}" class="btn btn-secondary" style="font-size:1.05rem; padding:0.85rem 1.75rem;">Download Travel Checklist</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    initPilgrimMap('yatriMap', 'Vaishno Devi');
});
</script>
@endpush
