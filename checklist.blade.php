@extends('layouts.app')

@section('title', 'Pilgrim Travel Checklist & Packing Essentials – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:960px;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
            <div>
                <span class="section-tag">Essential Preparation</span>
                <h1 class="section-title" style="margin-bottom:0.25rem;">Sacred Yatra Travel Checklist</h1>
                <p style="color:#64748b; margin:0;">Standardized packing and documentation checklist for a safe, hassle-free pilgrimage.</p>
            </div>
            <button onclick="window.print()" class="btn btn-navy">🖨️ Print / Save as PDF</button>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
            <!-- Category 1: Documents & Identity -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <h3 style="color:#0a192f; font-size:1.15rem; font-weight:800; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                    <span>🆔</span> 1. Mandatory Identity & Documents
                </h3>
                <ul style="list-style:none; display:flex; flex-direction:column; gap:0.75rem; font-size:0.92rem; color:#334155;">
                    <li><input type="checkbox"> <strong>Original Govt Photo ID</strong> (Aadhaar, Voter ID, or Passport)</li>
                    <li><input type="checkbox"> Physical photocopies of Aadhaar for RFID slip issuance</li>
                    <li><input type="checkbox"> Train / Bus / Flight confirmed tickets</li>
                    <li><input type="checkbox"> Medical fitness certificate (mandatory for Amarnath / Kedarnath)</li>
                    <li><input type="checkbox"> 2 Passport-sized photographs per passenger</li>
                </ul>
            </div>

            <!-- Category 2: Medical & First Aid -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <h3 style="color:#0a192f; font-size:1.15rem; font-weight:800; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                    <span>💊</span> 2. Personal Medical & Hygiene Kit
                </h3>
                <ul style="list-style:none; display:flex; flex-direction:column; gap:0.75rem; font-size:0.92rem; color:#334155;">
                    <li><input type="checkbox"> <strong>Daily Prescription Medication</strong> (with doctor prescription)</li>
                    <li><input type="checkbox"> Pain relief spray/balm & blister adhesive plasters</li>
                    <li><input type="checkbox"> Oral Rehydration Salts (ORS) & glucose packets</li>
                    <li><input type="checkbox"> High-altitude Diamox (under strict physician guidance)</li>
                    <li><input type="checkbox"> Hand sanitizer & antiseptic wet wipes</li>
                </ul>
            </div>

            <!-- Category 3: Footwear & Gear -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <h3 style="color:#0a192f; font-size:1.15rem; font-weight:800; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                    <span>👟</span> 3. Footwear & Mountain Gear
                </h3>
                <ul style="list-style:none; display:flex; flex-direction:column; gap:0.75rem; font-size:0.92rem; color:#334155;">
                    <li><input type="checkbox"> <strong>Broken-in Walking Shoes</strong> (with deep anti-skid rubber tread)</li>
                    <li><input type="checkbox"> 3-4 Pairs of thick, dry cotton/wool athletic socks</li>
                    <li><input type="checkbox"> Sturdy walking stick / trekking pole</li>
                    <li><input type="checkbox"> Waterproof rain poncho / lightweight raincoat</li>
                    <li><input type="checkbox"> Compact LED headlamp or pocket torch</li>
                </ul>
            </div>

            <!-- Category 4: Clothing & Warmth -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <h3 style="color:#0a192f; font-size:1.15rem; font-weight:800; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                    <span>🧥</span> 4. Sacred & Weather Clothing
                </h3>
                <ul style="list-style:none; display:flex; flex-direction:column; gap:0.75rem; font-size:0.92rem; color:#334155;">
                    <li><input type="checkbox"> Traditional modest clothing for Temple sanctum entry</li>
                    <li><input type="checkbox"> Windproof jacket and thermal inners</li>
                    <li><input type="checkbox"> Woolen monkey cap / muffler and gloves for night winds</li>
                    <li><input type="checkbox"> Power bank (portable phone charger) & charging cables</li>
                    <li><input type="checkbox"> Sufficient physical cash (ATMs at high altitude frequently run dry)</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
