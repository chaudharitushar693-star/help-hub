@extends('layouts.app')

@section('title', 'Privacy Policy & Consent-Based Location Handling – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:860px;">
        <span class="section-tag">User Trust & Protection</span>
        <h1 class="section-title">Privacy Policy</h1>
        <p style="font-size:0.9rem; color:#64748b; margin-bottom:2rem;">Last Updated: November 2026 | Compliant with Digital Personal Data Protection (DPDP) Act Guidelines</p>

        <div style="color:#334155; font-size:0.95rem; line-height:1.7; display:flex; flex-direction:column; gap:1.5rem;">
            <p>
                At <strong>YATRI SAHAYATA</strong> (developed by Nidhi Chaudhary, SATI Vidisha), we prioritize the safety and privacy of every sacred pilgrim. This policy outlines how information is handled across our web application.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">1. Consent-Based Geolocation Sharing</h3>
            <p>
                Location data (GPS latitude and longitude) is collected <strong>only when explicitly requested by the user</strong> via the "Share Current GPS" button inside the Emergency SOS modal. Geolocation is used solely to route emergency medical and volunteer assistance to your exact track coordinates and is never shared with third-party advertisers or commercial brokers.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">2. Pilgrim Account & Profile Information</h3>
            <p>
                When creating a voluntary pilgrim account, we collect your name, email, phone number, and optional emergency medical notes (e.g. blood group, accessibility requirements). These details allow the Smart Planner to tailor itineraries and provide volunteers with critical life-saving context during emergency requests.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">3. Data Security & Storage</h3>
            <p>
                User passwords are cryptographic one-way hashes (bcrypt / salted SHA-256). Form submissions are safeguarded by Cross-Site Request Forgery (CSRF) tokens, SQL injection defenses via prepared statements, and role-based access control.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">4. Independent Academic Scope</h3>
            <p>
                Yatri Sahayata is an independent academic project and does not transfer personal records to government databases or Shrine Boards unless authorized by the pilgrim in connection with an emergency escalation.
            </p>
        </div>
    </div>
</section>
@endsection
