<!-- Prominent Emergency SOS Assistance Modal -->
<div id="sosModal" class="modal-backdrop">
    <div class="modal-content">
        <button id="closeSosBtn" type="button" class="modal-close" aria-label="Close Modal">&times;</button>

        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
            <div style="background:#fee2e2; color:#ef4444; width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">🚨</div>
            <div>
                <h3 style="color:#b91c1c; font-size:1.35rem; font-weight:800; margin:0;">Emergency Assistance Alert</h3>
                <span style="font-size:0.8rem; color:#64748b;">Ground Coordinator & Medical Triage Form</span>
            </div>
        </div>

        <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:0.85rem; font-size:0.82rem; color:#9f1239; margin-bottom:1.25rem; line-height:1.5;">
            <strong>⚠️ Immediate Life-Threatening Crisis?</strong><br>
            Please immediately dial <strong>112 (National Emergency)</strong> or <strong>108 (Ambulance)</strong> on your mobile phone. This portal notifies nearby verified volunteer sevadars and desk coordinators.
        </div>

        <form id="sosRequestForm" action="{{ route('assistance.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="request_type" value="emergency_sos">
            <input type="hidden" name="priority" value="critical">
            <input type="hidden" id="sosLatitude" name="latitude" value="">
            <input type="hidden" id="sosLongitude" name="longitude" value="">

            <div class="form-group">
                <label class="form-label" for="sosPilgrimName">Pilgrim / Contact Person Name *</label>
                <input type="text" id="sosPilgrimName" name="pilgrim_name" class="form-control" placeholder="e.g. Ramesh Kumar" required value="{{ Auth::check() ? Auth::user()->name : '' }}">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label class="form-label" for="sosContact">Active Mobile Number *</label>
                    <input type="tel" id="sosContact" name="contact_number" class="form-control" placeholder="+91 98260 12345" required value="{{ Auth::check() ? Auth::user()->phone : '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="sosCircuit">Pilgrim Circuit *</label>
                    <select id="sosCircuit" name="circuit" class="form-control" required>
                        <option value="Vaishno Devi">Vaishno Devi (Katra)</option>
                        <option value="Ujjain Mahakal">Ujjain Mahakaleshwar</option>
                        <option value="Char Dham">Char Dham (Kedarnath/Badrinath)</option>
                        <option value="Omkareshwar">Omkareshwar Jyotirlinga</option>
                        <option value="Varanasi Kashi">Kashi Vishwanath</option>
                        <option value="Other">Other Pilgrim Circuit</option>
                    </select>
                </div>
            </div>

            <!-- Consent-based Location Sharing -->
            <div class="form-group" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:0.85rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                    <label class="form-label" style="margin:0; font-size:0.85rem;">📍 Consent-Based GPS Sharing</label>
                    <button type="button" id="shareLocationBtn" class="btn btn-sm btn-navy" style="font-size:0.75rem; padding:0.3rem 0.65rem;">Share Current GPS</button>
                </div>
                <div id="locationStatus" style="font-size:0.75rem; color:#64748b;">
                    Tap button to attach precise coordinates from your device with your consent.
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="sosCurrentLocation">Exact Location / Track Landmark *</label>
                <input type="text" id="sosCurrentLocation" name="current_location" class="form-control" placeholder="e.g. Near Ardhkuwari Track KM 4.5, bench facing valley" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="sosDescription">Urgent Assistance Details *</label>
                <textarea id="sosDescription" name="description" rows="3" class="form-control" placeholder="Describe emergency: e.g. severe shortness of breath, elderly person unable to walk, chest discomfort, lost child..." required></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('sosModal').style.display='none'" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-sos" style="font-size:0.95rem; padding:0.65rem 1.5rem;">Submit Emergency Assistance Request</button>
            </div>
        </form>
    </div>
</div>
