@extends('layouts.app')

@section('title', 'Pilgrim Profile & Emergency Details – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:2.5rem 0;">
    <div class="container" style="max-width:760px;">
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-sm);">
            <div style="margin-bottom:2rem; border-bottom:1px solid #f1f5f9; padding-bottom:1.25rem;">
                <span class="section-tag">Account & Medical Safety</span>
                <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0.25rem 0;">Pilgrim Profile & Emergency Preferences</h1>
                <p style="color:#64748b; font-size:0.9rem; margin:0;">
                    These emergency details are securely stored to auto-assist ground medical triage in case an SOS is triggered.
                </p>
            </div>

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="profName">Full Name *</label>
                        <input type="text" id="profName" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="profPhone">Mobile Phone *</label>
                        <input type="tel" id="profPhone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="profEmail">Registered Email</label>
                    <input type="email" id="profEmail" class="form-control" value="{{ $user->email }}" disabled style="background:#f1f5f9; cursor:not-allowed;">
                    <span style="font-size:0.75rem; color:#64748b;">Email address serves as unique identifier and cannot be changed here.</span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="profEmName">Emergency Contact Person Name</label>
                        <input type="text" id="profEmName" name="emergency_contact_name" class="form-control" placeholder="e.g. Anil Sharma (Brother)" value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="profEmPhone">Emergency Contact Phone Number</label>
                        <input type="tel" id="profEmPhone" name="emergency_contact_phone" class="form-control" placeholder="+91 98260 54321" value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="profBlood">Blood Group</label>
                        <select id="profBlood" name="blood_group" class="form-control">
                            <option value="">Select</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ (old('blood_group', $profile->blood_group) === $bg) ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="profCity">Home City</label>
                        <input type="text" id="profCity" name="city" class="form-control" placeholder="e.g. Vidisha" value="{{ old('city', $profile->city) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="profState">Home State</label>
                        <input type="text" id="profState" name="state" class="form-control" placeholder="e.g. Madhya Pradesh" value="{{ old('state', $profile->state) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="profMed">Pre-Existing Medical Conditions / Regular Medication</label>
                    <textarea id="profMed" name="medical_conditions" rows="2" class="form-control" placeholder="e.g. Mild Hypertension, Diabetes, Asthma inhaler required...">{{ old('medical_conditions', $profile->medical_conditions) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="profAccess">Accessibility Needs (Elders / Differently Abled)</label>
                    <input type="text" id="profAccess" name="accessibility_needs" class="form-control" placeholder="e.g. Wheelchair assistance required for mountain climb" value="{{ old('accessibility_needs', $profile->accessibility_needs) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="profLang">Preferred Language</label>
                    <select id="profLang" name="preferred_language" class="form-control">
                        <option value="en" {{ old('preferred_language', $user->preferred_language) === 'en' ? 'selected' : '' }}>English</option>
                        <option value="hi" {{ old('preferred_language', $user->preferred_language) === 'hi' ? 'selected' : '' }}>हिन्दी (Hindi)</option>
                    </select>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:2rem;">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
