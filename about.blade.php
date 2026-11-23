@extends('layouts.app')

@section('title', 'About Yatri Sahayata – Academic Mission & Developer Credentials')

@section('content')
<section class="section" style="background:#ffffff; padding-top:3rem;">
    <div class="container" style="max-width:960px;">
        <span class="section-tag">Project Overview & Vision</span>
        <h1 class="section-title">Empowering Sacred Pilgrimages with Civic Technology</h1>

        <p style="font-size:1.15rem; color:#334155; line-height:1.7; margin-bottom:2rem;">
            <strong>YATRI SAHAYATA (Smart Pilgrim Assistance Platform)</strong> is an independent digital civic-tech solution architected to address logistical bottlenecks, emergency medical opacity, high-altitude vulnerabilities, and navigation challenges encountered by millions of yatris visiting sacred Indian pilgrimage circuits each year.
        </p>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:2rem; margin-bottom:3rem;">
            <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:1rem;">🎓 Academic Project & Developer Credentials</h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; font-size:0.95rem;">
                <div>
                    <div style="color:#64748b; font-size:0.8rem; text-transform:uppercase;">Principal Student Developer</div>
                    <div style="font-size:1.15rem; font-weight:700; color:#0a192f; margin-top:2px;">Nidhi Chaudhary</div>
                    <div style="color:#f59e0b; font-weight:600; margin-top:2px;">Enrollment: 0108CS241082</div>
                </div>
                <div>
                    <div style="color:#64748b; font-size:0.8rem; text-transform:uppercase;">Affiliated Academic Institution</div>
                    <div style="font-size:1.05rem; font-weight:700; color:#0a192f; margin-top:2px;">Samrat Ashok Technological Institute (SATI)</div>
                    <div style="color:#475569; margin-top:2px;">Vidisha, Madhya Pradesh, India</div>
                </div>
                <div>
                    <div style="color:#64748b; font-size:0.8rem; text-transform:uppercase;">Hosting & Deployment Reference</div>
                    <div style="margin-top:2px;"><a href="https://nidhi-chaudhary.fwh.is/" target="_blank" style="color:#0284c7; font-weight:600; text-decoration:underline;">https://nidhi-chaudhary.fwh.is/</a></div>
                </div>
                <div>
                    <div style="color:#64748b; font-size:0.8rem; text-transform:uppercase;">Architecture Paradigm</div>
                    <div style="margin-top:2px; font-weight:600; color:#0a192f;">Modular Monolith (Laravel 11 / PHP 8.3 + MySQL 8)</div>
                </div>
            </div>
        </div>

        <h2 style="font-size:1.6rem; font-weight:800; color:#0a192f; margin-bottom:1rem;">Core Engineering Objectives</h2>
        <div class="cards-grid" style="margin-bottom:3rem;">
            <div class="yatri-card">
                <span class="card-badge badge-info">1. Real-Time Triage</span>
                <h4 style="font-size:1.1rem; color:#0a192f; margin-bottom:0.5rem;">Emergency SOS & Directory</h4>
                <p style="font-size:0.88rem; color:#64748b;">Instant consent-based GPS capturing and dispatch routing to verified mountain medical aid posts and ambulance hotlines.</p>
            </div>
            <div class="yatri-card">
                <span class="card-badge badge-gold">2. Smart Itinerary</span>
                <h4 style="font-size:1.1rem; color:#0a192f; margin-bottom:0.5rem;">Rule-Based Yatra Planner</h4>
                <p style="font-size:0.88rem; color:#64748b;">Intelligent scheduling algorithm factoring in senior citizen walking speed, battery car availability, and high-altitude acclimatization stops.</p>
            </div>
            <div class="yatri-card">
                <span class="card-badge badge-verified">3. Ground Truth Verification</span>
                <h4 style="font-size:1.1rem; color:#0a192f; margin-bottom:0.5rem;">Verified Infrastructure</h4>
                <p style="font-size:0.88rem; color:#64748b;">Rigorous directory moderation of trust dharamshalas, computerized cloak rooms, wheelchair points, and official RFID counters.</p>
            </div>
        </div>

        <div style="background:#fffbeb; border-left:4px solid #f59e0b; padding:1.25rem 1.5rem; border-radius:0 8px 8px 0; margin-bottom:3rem;">
            <h4 style="color:#92400e; font-size:1rem; font-weight:700; margin-bottom:0.25rem;">Important Disclaimer & Governance Boundaries</h4>
            <p style="color:#78350f; font-size:0.88rem; line-height:1.6; margin:0;">
                YATRI SAHAYATA is strictly an independent civic and student software project. It does not claim official affiliation with the Shri Mata Vaishno Devi Shrine Board (SMVDSB), Shri Mahakaleshwar Temple Management Committee, Shri Badrinath Kedarnath Temple Committee (BKTC), or any statutory shrine trust. All bookings, VIP slips, and mandatory biometric cards must be obtained through respective official administration channels.
            </p>
        </div>
    </div>
</section>
@endsection
