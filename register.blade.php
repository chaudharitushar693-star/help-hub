@extends('layouts.app')

@section('title', 'Register Pilgrim Account – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:3rem 0;">
    <div class="container" style="max-width:540px;">
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-lg);">
            <div style="text-align:center; margin-bottom:2rem;">
                <div class="brand-logo-icon" style="margin:0 auto 1rem; width:3.5rem; height:3.5rem; font-size:1.75rem;">🕉️</div>
                <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">Create Pilgrim Account</h1>
                <p style="color:#64748b; font-size:0.88rem; margin-top:0.25rem;">Unlock personalized smart planning, emergency profile sync & requests</p>
            </div>

            @if($errors->any())
                <div style="background:#fee2e2; border:1px solid #fecdd3; color:#991b1b; padding:0.75rem 1rem; border-radius:8px; font-size:0.85rem; margin-bottom:1.5rem;">
                    @foreach($errors->all() as $err)
                        <div>⚠️ {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="regName">Full Name *</label>
                    <input type="text" id="regName" name="name" class="form-control" placeholder="e.g. Ramesh Kumar Sharma" value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="regEmail">Email Address *</label>
                        <input type="email" id="regEmail" name="email" class="form-control" placeholder="ramesh@example.com" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="regPhone">Mobile Phone *</label>
                        <input type="tel" id="regPhone" name="phone" class="form-control" placeholder="+91 98260 12345" value="{{ old('phone') }}" required autocomplete="tel">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="regPassword">Password *</label>
                        <input type="password" id="regPassword" name="password" class="form-control" placeholder="Minimum 6 characters" required autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="regPasswordConfirm">Confirm Password *</label>
                        <input type="password" id="regPasswordConfirm" name="password_confirmation" class="form-control" placeholder="Re-type password" required autocomplete="new-password">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="regCity">Home City</label>
                        <input type="text" id="regCity" name="city" class="form-control" placeholder="e.g. Indore" value="{{ old('city') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="regState">State</label>
                        <input type="text" id="regState" name="state" class="form-control" placeholder="e.g. Madhya Pradesh" value="{{ old('state') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regLang">Preferred Interface Language</label>
                    <select id="regLang" name="preferred_language" class="form-control">
                        <option value="en" {{ old('preferred_language') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="hi" {{ old('preferred_language') === 'hi' ? 'selected' : '' }}>हिन्दी (Hindi)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display:flex; align-items:flex-start; gap:0.5rem; font-size:0.85rem; color:#475569; cursor:pointer;">
                        <input type="checkbox" name="terms" required style="margin-top:0.25rem;">
                        <span>I understand Yatri Sahayata is an independent assistance initiative by Nidhi Chaudhary (SATI Vidisha) and agree to the <a href="{{ route('terms') }}" target="_blank" style="color:#f59e0b;">Terms</a> & <a href="{{ route('privacy') }}" target="_blank" style="color:#f59e0b;">Privacy Policy</a>.</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; font-size:1rem; padding:0.75rem;">Register Free Account</button>
            </form>

            <div style="margin-top:1.5rem; text-align:center; font-size:0.88rem; color:#64748b;">
                Already registered? <a href="{{ route('login') }}" style="color:#0284c7; font-weight:600; text-decoration:none;">Sign In</a>
            </div>
        </div>
    </div>
</section>
@endsection
