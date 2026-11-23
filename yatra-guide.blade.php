@extends('layouts.app')

@section('title', 'Complete Yatra Guide & Holy Rules – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:960px;">
        <div class="section-header">
            <span class="section-tag">Pilgrim Hand-Guide</span>
            <h1 class="section-title">The Complete Holy Yatra Guide</h1>
            <p class="section-desc">Practical ground knowledge every pilgrim must know before setting foot on the sacred track.</p>
        </div>

        <div style="display:flex; flex-direction:column; gap:2.5rem;">
            <!-- Stage 1 -->
            <div style="display:flex; gap:1.5rem;">
                <div style="background:#0a192f; color:#fbbf24; width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.4rem; font-weight:800; flex-shrink:0;">1</div>
                <div>
                    <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:0.5rem;">Mandatory RFID Yatra Card Acquisition</h3>
                    <p style="color:#475569; font-size:0.95rem; line-height:1.6;">
                        For shrines like Mata Vaishno Devi, Char Dham, and Amarnath, an official biometric RFID card is <strong>mandatory and issued 100% free of cost</strong>.
                    </p>
                    <ul style="color:#334155; font-size:0.92rem; line-height:1.7; margin-left:1.25rem;">
                        <li><strong>Issuing Centers:</strong> Katra Railway Station, Katra Bus Stand, Banganga Checkpost.</li>
                        <li><strong>Documents Required:</strong> Physical original Aadhaar Card or Passport for every traveler (including children).</li>
                        <li><strong>Important Rule:</strong> You cannot pass the Banganga RFID gate without this card hung around your neck.</li>
                    </ul>
                </div>
            </div>

            <!-- Stage 2 -->
            <div style="display:flex; gap:1.5rem;">
                <div style="background:#0a192f; color:#fbbf24; width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.4rem; font-weight:800; flex-shrink:0;">2</div>
                <div>
                    <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:0.5rem;">Luggage, Valuables & Free Cloakrooms</h3>
                    <p style="color:#475569; font-size:0.95rem; line-height:1.6;">
                        Entering the holy cave or temple sanctum requires strict security compliance.
                    </p>
                    <ul style="color:#334155; font-size:0.92rem; line-height:1.7; margin-left:1.25rem;">
                        <li><strong>Prohibited Items in Sanctum:</strong> Mobile phones, electronic smartwatches, cameras, leather belts, and leather wallets.</li>
                        <li><strong>Safe Storage:</strong> Use Shrine Board computerized free cloakrooms located at Banganga, Ardhkuwari, and Bhawan Gate 2.</li>
                        <li><strong>Token Receipt:</strong> Keep the stamped metal or plastic token safely; lost token procedures take 2-3 hours of identity verification.</li>
                    </ul>
                </div>
            </div>

            <!-- Stage 3 -->
            <div style="display:flex; gap:1.5rem;">
                <div style="background:#0a192f; color:#fbbf24; width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.4rem; font-weight:800; flex-shrink:0;">3</div>
                <div>
                    <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:0.5rem;">Senior Citizens, Battery Cars & Wheelchairs</h3>
                    <p style="color:#475569; font-size:0.95rem; line-height:1.6;">
                        Special care facilities are established along gentler track routes for elders and medically frail pilgrims.
                    </p>
                    <ul style="color:#334155; font-size:0.92rem; line-height:1.7; margin-left:1.25rem;">
                        <li><strong>Battery Car (Sanjichhat to Bhawan):</strong> Priority for yatris aged 60+ and medically disabled persons. Operates 07:00 AM to 05:00 PM.</li>
                        <li><strong>Wheelchairs:</strong> Available on deposit of government ID at Banganga, Sanjichhat, and Bhawan facilitation centers.</li>
                        <li><strong>Ponies / Palkis:</strong> Only hire authorized operators wearing laminated Shrine Board badges. Always insist on computerized prepaid tokens.</li>
                    </ul>
                </div>
            </div>

            <!-- Stage 4 -->
            <div style="display:flex; gap:1.5rem;">
                <div style="background:#0a192f; color:#fbbf24; width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.4rem; font-weight:800; flex-shrink:0;">4</div>
                <div>
                    <h3 style="color:#991b1b; font-size:1.3rem; font-weight:800; margin-bottom:0.5rem;">Medical Emergency & Oxygen Booth Protocol</h3>
                    <p style="color:#475569; font-size:0.95rem; line-height:1.6;">
                        Every 2 to 3 kilometers along the track features a government medical dispensary.
                    </p>
                    <ul style="color:#334155; font-size:0.92rem; line-height:1.7; margin-left:1.25rem;">
                        <li>If feeling dizzy or breathless, sit on a track bench immediately and alert fellow yatris or sevadars.</li>
                        <li>Free oxygen inhalation and pulse oximeter testing are available at Ardhkuwari, Himkoti, Sanjichhat, and Bhawan.</li>
                        <li>Use the **Emergency SOS** button on this platform or call <strong>108 / 112</strong> immediately.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="text-align:center; margin-top:3.5rem;">
            <a href="{{ route('planner.index') }}" class="btn btn-primary" style="font-size:1.1rem; padding:0.85rem 2rem;">
                Launch Smart Yatra Planner &rarr;
            </a>
        </div>
    </div>
</section>
@endsection
