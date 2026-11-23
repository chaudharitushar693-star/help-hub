<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="brand-wrap" style="margin-bottom:1rem;">
                    <div class="brand-logo-icon">🕉️</div>
                    <div class="brand-text">
                        <h3 style="font-size:1.25rem; font-weight:800; color:#ffffff;">YATRI SAHAYATA</h3>
                        <span style="color:#fbbf24;">Your Journey, Our Responsibility</span>
                    </div>
                </div>
                <p style="color:#cbd5e1; font-size:0.875rem; line-height:1.6; margin-bottom:1.25rem;">
                    A digital citizen and pilgrim assistance initiative created to offer real-time route guidance, emergency medical directory, RFID registration rules, and smart itinerary planning across major Indian sacred pilgrimage circuits.
                </p>
                <div class="developer-box">
                    <h5>🎓 Academic Project Attribution</h5>
                    <p>
                        <strong>Developer:</strong> Nidhi Chaudhary<br>
                        <strong>Enrollment Number:</strong> 0108CS241082<br>
                        <strong>Institution:</strong> Samrat Ashok Technological Institute (SATI), Vidisha, Madhya Pradesh<br>
                        <strong>Live Reference:</strong> <a href="https://nidhi-chaudhary.fwh.is/" target="_blank" style="color:#fbbf24; text-decoration:underline;">nidhi-chaudhary.fwh.is</a>
                    </p>
                </div>
            </div>

            <div class="footer-col">
                <h4>Pilgrim Services</h4>
                <ul>
                    <li><a href="{{ route('routes') }}?circuit=Vaishno+Devi">Vaishno Devi Track Guide</a></li>
                    <li><a href="{{ route('routes') }}?circuit=Ujjain+Mahakal">Ujjain Mahakal Jyotirlinga</a></li>
                    <li><a href="{{ route('routes') }}?circuit=Char+Dham">Char Dham Yatra Route</a></li>
                    <li><a href="{{ route('planner.index') }}">Smart Yatra Planner</a></li>
                    <li><a href="{{ route('medical') }}">Emergency Medical Directory</a></li>
                    <li><a href="{{ route('accommodation') }}">Verified Dharamshalas</a></li>
                    <li><a href="{{ route('checklist') }}">Downloadable Travel Checklist</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Assistance & Support</h4>
                <ul>
                    <li><a href="{{ route('assistance.track') }}">Track Assistance Request</a></li>
                    <li><a href="{{ route('announcements') }}">Live Safety Advisories</a></li>
                    <li><a href="{{ route('lost-found') }}">Lost & Found Portal</a></li>
                    <li><a href="{{ route('weather') }}">High-Altitude Weather</a></li>
                    <li><a href="{{ route('faqs') }}">Bilingual FAQs (हिन्दी / English)</a></li>
                    <li><a href="{{ route('contact') }}">Contact Coordination Desk</a></li>
                    <li><a href="{{ route('accessibility') }}">Accessibility Statement</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Emergency Helplines</h4>
                <div style="background:rgba(239, 68, 68, 0.15); border:1px solid rgba(239,68,68,0.3); border-radius:8px; padding:1rem; margin-bottom:1rem;">
                    <div style="color:#f87171; font-weight:700; font-size:0.85rem; margin-bottom:0.5rem;">🚨 CRITICAL HELPLINES</div>
                    <div style="font-size:0.82rem; color:#ffffff; line-height:1.7;">
                        • National Emergency: <strong>112</strong><br>
                        • Medical Ambulance: <strong>108</strong><br>
                        • Police Assistance: <strong>100</strong><br>
                        • Katra Trauma Post: <strong>01991-232044</strong><br>
                        • Ujjain Emergency Post: <strong>0734-2551444</strong>
                    </div>
                </div>
                <p style="font-size:0.75rem; color:#94a3b8; line-height:1.4;">
                    *Yatri Sahayata connects yatris with verified ground contacts and does not operate an independent paramilitary dispatch.
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; {{ date('Y') }} <strong>YATRI SAHAYATA</strong>. Designed & Developed by <strong>Nidhi Chaudhary</strong> (0108CS241082), SATI Vidisha.
            </div>
            <div style="display:flex; gap:1.25rem;">
                <a href="{{ route('privacy') }}" style="color:#94a3b8;">Privacy Policy</a>
                <a href="{{ route('terms') }}" style="color:#94a3b8;">Terms of Service</a>
                <a href="{{ route('about') }}" style="color:#94a3b8;">About Project</a>
            </div>
        </div>
    </div>
</footer>
