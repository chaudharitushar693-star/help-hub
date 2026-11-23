@extends('layouts.app')

@section('title', 'Accessibility Commitment (WCAG 2.1) – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:860px;">
        <span class="section-tag">Universal Inclusivity</span>
        <h1 class="section-title">Accessibility Statement</h1>
        <p style="font-size:0.9rem; color:#64748b; margin-bottom:2rem;">Designed in conformance with Web Content Accessibility Guidelines (WCAG 2.1 Level AA)</p>

        <div style="color:#334155; font-size:0.95rem; line-height:1.7; display:flex; flex-direction:column; gap:1.5rem;">
            <p>
                Pilgrimages are undertaken by people of all ages, including senior citizens (65+ years) and differently-abled devotees. Yatri Sahayata has been built from the ground up to ensure high accessibility across modern mobile and desktop screens.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">1. Visual Contrast & Clear Typography</h3>
            <p>
                All text elements maintain a minimum contrast ratio of 4.5:1 against their backgrounds. Deep navy blue (#0a192f) paired with crisp white (#ffffff) ensures maximum readability even in bright outdoor sunlight on mountain tracks.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">2. Screen Reader & Keyboard Navigation</h3>
            <p>
                Interactive buttons, navigation menus, and emergency modals carry explicit <code>aria-label</code> and <code>role</code> attributes. Full keyboard navigation (Tab / Shift-Tab / Enter / Esc) is supported across all forms.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">3. Bilingual English & Hindi Inclusivity</h3>
            <p>
                Recognizing that millions of Indian pilgrims communicate natively in Hindi, all critical emergency prompts, FAQs, and AI assistant dialogues are available in both English and शुद्ध हिन्दी.
            </p>

            <h3 style="color:#0a192f; font-size:1.15rem; font-weight:700;">4. Feedback on Accessibility</h3>
            <p>
                If you encounter any barrier while accessing this platform, please reach out to developer <strong>Nidhi Chaudhary</strong> via our <a href="{{ route('contact') }}" style="color:#0284c7; text-decoration:underline;">Contact Desk</a>.
            </p>
        </div>
    </div>
</section>
@endsection
