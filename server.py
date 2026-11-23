#!/usr/bin/env python3
"""
======================================================================
YATRI SAHAYATA – Smart Pilgrim Assistance Platform
Standalone Zero-Dependency Local Production Server
Developer: Nidhi Chaudhary | Enrollment: 0108CS241082
Samrat Ashok Technological Institute (SATI), Vidisha, Madhya Pradesh
Hosting Reference: https://nidhi-chaudhary.fwh.is/
======================================================================
"""

import os
import sys
import json
import sqlite3
import hashlib
import secrets
import posixpath
import mimetypes
from datetime import datetime, date
from http.server import SimpleHTTPRequestHandler, HTTPServer
from urllib.parse import urlparse, parse_qs, unquote
from http import cookies

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DB_PATH = os.path.join(BASE_DIR, "database", "database.sqlite")
PUBLIC_DIR = os.path.join(BASE_DIR, "public")
PORT = int(os.environ.get("PORT", 8000))

# Session Store
SESSIONS = {}

def get_db():
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    return conn

def hash_pw(pw: str) -> str:
    return hashlib.sha256(pw.encode('utf-8')).hexdigest()

def get_current_user(handler):
    cookie_header = handler.headers.get("Cookie")
    if not cookie_header:
        return None
    c = cookies.SimpleCookie()
    try:
        c.load(cookie_header)
    except Exception:
        return None
    session_id = c.get("yatri_session")
    if not session_id or session_id.value not in SESSIONS:
        return None
    user_id = SESSIONS[session_id.value]
    with get_db() as conn:
        cur = conn.cursor()
        cur.execute("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?", (user_id,))
        row = cur.fetchone()
        return dict(row) if row else None

# Helper HTML rendering components
def render_header(title="YATRI SAHAYATA", user=None, current_path=""):
    user_action_html = ""
    if user:
        if user.get("role_name") == "admin" or user.get("role_id") == 1:
            portal_link = '<a href="/admin" class="btn btn-sm btn-outline-gold">Admin Portal</a>'
        else:
            portal_link = '<a href="/dashboard" class="btn btn-sm btn-outline-gold">My Dashboard</a>'
        user_action_html = f'''
            {portal_link}
            <a href="/logout" class="btn btn-sm btn-secondary" style="padding:0.4rem 0.65rem;">Logout</a>
        '''
    else:
        user_action_html = '''
            <a href="/login" class="btn btn-sm btn-secondary">Sign In</a>
            <a href="/register" class="btn btn-sm btn-primary">Register</a>
        '''

    active_home = 'active' if current_path in ('/', '/home') else ''
    active_routes = 'active' if current_path.startswith('/routes') else ''
    active_planner = 'active' if current_path.startswith('/planner') else ''
    active_medical = 'active' if current_path.startswith('/medical') else ''
    active_acc = 'active' if current_path.startswith('/accommodation') else ''
    active_adv = 'active' if current_path.startswith('/announcements') else ''
    active_lost = 'active' if current_path.startswith('/lost-found') else ''
    active_faqs = 'active' if current_path.startswith('/faqs') else ''

    return f'''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="YATRI SAHAYATA – Smart Pilgrim Assistance Platform by Nidhi Chaudhary (0108CS241082), SATI Vidisha.">
    <meta name="author" content="Nidhi Chaudhary (0108CS241082), Samrat Ashok Technological Institute, Vidisha, MP">
    <title>{title}</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="/css/yatri-main.css">
</head>
<body>
    <div class="disclaimer-banner">
        <span>⚠️ <strong>Independent Assistance Initiative:</strong> YATRI SAHAYATA is an academic and digital citizen assistance project developed by <strong>Nidhi Chaudhary</strong> (Enrollment: 0108CS241082, SATI Vidisha). It is <strong>NOT</strong> an official Shrine Board or Government entity.</span>
    </div>
    <header class="navbar">
        <div class="container nav-inner">
            <a href="/" class="brand-wrap">
                <div class="brand-logo-icon">🕉️</div>
                <div class="brand-text">
                    <h1>YATRI SAHAYATA</h1>
                    <span>Your Journey, Our Responsibility</span>
                </div>
            </a>
            <ul class="nav-links">
                <li><a href="/" class="{active_home}">Home</a></li>
                <li><a href="/routes" class="{active_routes}">Routes & Map</a></li>
                <li><a href="/planner" class="{active_planner}">Smart Planner</a></li>
                <li><a href="/medical" class="{active_medical}">Medical</a></li>
                <li><a href="/accommodation" class="{active_acc}">Stay</a></li>
                <li><a href="/announcements" class="{active_adv}">Advisories</a></li>
                <li><a href="/lost-found" class="{active_lost}">Lost & Found</a></li>
                <li><a href="/faqs" class="{active_faqs}">FAQs</a></li>
            </ul>
            <div class="nav-actions">
                <select id="globalLanguageSelect" class="lang-select">
                    <option value="en">English (EN)</option>
                    <option value="hi">हिन्दी (HI)</option>
                </select>
                <button id="openSosBtn" type="button" class="btn-sos">
                    <span>🚨</span> SOS EMERGENCY
                </button>
                {user_action_html}
            </div>
        </div>
    </header>
    <main>
'''

def render_footer():
    return f'''
    </main>
    <!-- Emergency SOS Modal -->
    <div id="sosModal" class="modal-backdrop">
        <div class="modal-content">
            <button id="closeSosBtn" type="button" class="modal-close">&times;</button>
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                <div style="background:#fee2e2; color:#ef4444; width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">🚨</div>
                <div>
                    <h3 style="color:#b91c1c; font-size:1.35rem; font-weight:800; margin:0;">Emergency Assistance Alert</h3>
                    <span style="font-size:0.8rem; color:#64748b;">Ground Coordinator & Medical Triage Form</span>
                </div>
            </div>
            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; padding:0.85rem; font-size:0.82rem; color:#9f1239; margin-bottom:1.25rem;">
                <strong>⚠️ Immediate Crisis?</strong> Call <strong>112 (National Emergency)</strong> or <strong>108 (Ambulance)</strong>. This portal alerts volunteer sevadars and desk coordinators.
            </div>
            <form id="sosRequestForm" action="/assistance/submit" method="POST">
                <input type="hidden" name="request_type" value="emergency_sos">
                <input type="hidden" name="priority" value="critical">
                <input type="hidden" id="sosLatitude" name="latitude" value="">
                <input type="hidden" id="sosLongitude" name="longitude" value="">
                <div class="form-group">
                    <label class="form-label">Pilgrim / Contact Person Name *</label>
                    <input type="text" name="pilgrim_name" class="form-control" placeholder="e.g. Ramesh Kumar" required>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Active Mobile Number *</label>
                        <input type="tel" name="contact_number" class="form-control" placeholder="+91 98260 12345" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilgrim Circuit *</label>
                        <select name="circuit" class="form-control" required>
                            <option value="Vaishno Devi">Vaishno Devi (Katra)</option>
                            <option value="Ujjain Mahakal">Ujjain Mahakaleshwar</option>
                            <option value="Char Dham">Char Dham (Kedarnath/Badrinath)</option>
                            <option value="Omkareshwar">Omkareshwar Jyotirlinga</option>
                            <option value="Varanasi Kashi">Kashi Vishwanath</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:0.85rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <label class="form-label" style="margin:0; font-size:0.85rem;">📍 Consent-Based GPS Sharing</label>
                        <button type="button" id="shareLocationBtn" class="btn btn-sm btn-navy" style="font-size:0.75rem;">Share Current GPS</button>
                    </div>
                    <div id="locationStatus" style="font-size:0.75rem; color:#64748b; margin-top:4px;">
                        Tap to attach precise GPS coordinates from your device with your consent.
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Exact Location / Milestone *</label>
                    <input type="text" id="sosCurrentLocation" name="current_location" class="form-control" placeholder="e.g. Ardhkuwari Track KM 4.5, bench facing valley" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Urgent Details *</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="e.g. severe shortness of breath, elderly person unable to walk..." required></textarea>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem;">
                    <button type="button" onclick="document.getElementById('sosModal').style.display='none'" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-sos">Submit Emergency Alert</button>
                </div>
            </form>
        </div>
    </div>

    <!-- AI Floating Chatbot Widget -->
    <div id="aiChatBubble" class="ai-chat-bubble" title="Chat with AI Yatri Assistant">🤖</div>
    <div id="aiChatWindow" class="ai-chat-window">
        <div class="ai-chat-header">
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <div style="font-size:1.25rem;">🕉️</div>
                <div>
                    <strong style="font-size:0.95rem; display:block;">AI Yatri Assistant</strong>
                    <span style="font-size:0.72rem; color:#cbd5e1;">Bilingual Pilgrim Guide</span>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <select id="aiLanguageSelect" style="background:#1e293b; color:#fff; border:1px solid #334155; border-radius:4px; font-size:0.75rem; padding:2px 4px;">
                    <option value="en">English</option>
                    <option value="hi">हिन्दी</option>
                </select>
                <button id="aiChatClose" type="button" style="background:none; border:none; color:#cbd5e1; font-size:1.2rem; cursor:pointer;">&times;</button>
            </div>
        </div>
        <div style="padding:0.5rem 0.75rem; background:#f1f5f9; display:flex; gap:0.4rem; overflow-x:auto; border-bottom:1px solid #e2e8f0;">
            <button type="button" onclick="askAiQuick('How to get RFID Yatra Parchi?')" style="white-space:nowrap; background:#fff; border:1px solid #cbd5e1; border-radius:12px; font-size:0.72rem; padding:2px 8px; cursor:pointer;">RFID Slip</button>
            <button type="button" onclick="askAiQuick('Battery Car rules for seniors')" style="white-space:nowrap; background:#fff; border:1px solid #cbd5e1; border-radius:12px; font-size:0.72rem; padding:2px 8px; cursor:pointer;">Senior Support</button>
            <button type="button" onclick="askAiQuick('Emergency medical post locations')" style="white-space:nowrap; background:#fff; border:1px solid #cbd5e1; border-radius:12px; font-size:0.72rem; padding:2px 8px; cursor:pointer;">Medical Aid</button>
        </div>
        <div id="aiChatMessages" class="ai-chat-messages">
            <div class="chat-msg bot">
                🙏 <strong>Jai Mata Di / Har Har Mahadev!</strong><br>
                Welcome to <strong>Yatri Sahayata</strong>. Ask me anything about RFID slips, battery cars, oxygen posts, or route shelters.
                <div style="font-size:0.68rem; color:#64748b; margin-top:0.35rem;">Guardrail: Verified shrine logistics only. In medical emergency, tap the SOS button.</div>
            </div>
        </div>
        <form id="aiChatForm" class="ai-chat-input-bar">
            <input type="text" id="aiChatInput" class="form-control" style="font-size:0.85rem; padding:0.5rem 0.75rem;" placeholder="Ask in Hindi or English..." required autocomplete="off">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem 0.85rem; font-size:0.85rem;">Send</button>
        </form>
    </div>

    <!-- Production Footer -->
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
                            <strong>Institution:</strong> Samrat Ashok Technological Institute (SATI), Vidisha, MP<br>
                            <strong>Deployment Host:</strong> <a href="https://nidhi-chaudhary.fwh.is/" target="_blank" style="color:#fbbf24; text-decoration:underline;">nidhi-chaudhary.fwh.is</a>
                        </p>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Pilgrim Services</h4>
                    <ul>
                        <li><a href="/routes?circuit=Vaishno+Devi">Vaishno Devi Track Guide</a></li>
                        <li><a href="/routes?circuit=Ujjain+Mahakal">Ujjain Mahakal Jyotirlinga</a></li>
                        <li><a href="/routes?circuit=Char+Dham">Char Dham Yatra Route</a></li>
                        <li><a href="/planner">Smart Yatra Planner</a></li>
                        <li><a href="/medical">Emergency Medical Directory</a></li>
                        <li><a href="/accommodation">Verified Dharamshalas</a></li>
                        <li><a href="/checklist">Travel Packing Checklist</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Assistance & Support</h4>
                    <ul>
                        <li><a href="/track">Track Assistance Request</a></li>
                        <li><a href="/announcements">Live Safety Advisories</a></li>
                        <li><a href="/lost-found">Lost & Found Portal</a></li>
                        <li><a href="/weather">High-Altitude Weather</a></li>
                        <li><a href="/faqs">Bilingual FAQs (हिन्दी / English)</a></li>
                        <li><a href="/contact">Contact Coordination Desk</a></li>
                        <li><a href="/accessibility">Accessibility Statement</a></li>
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
                </div>
            </div>
            <div class="footer-bottom">
                <div>
                    &copy; 2026 <strong>YATRI SAHAYATA</strong>. Designed & Developed by <strong>Nidhi Chaudhary</strong> (0108CS241082), SATI Vidisha.
                </div>
                <div style="display:flex; gap:1.25rem;">
                    <a href="/privacy" style="color:#94a3b8;">Privacy Policy</a>
                    <a href="/terms" style="color:#94a3b8;">Terms of Service</a>
                    <a href="/about" style="color:#94a3b8;">About Project</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="/js/yatri-app.js"></script>
    <script src="/js/yatri-map.js"></script>
    <script src="/js/yatri-ai.js"></script>
</body>
</html>
'''

class YatriRequestHandler(SimpleHTTPRequestHandler):
    def log_message(self, format, *args):
        # Clean terminal logging
        sys.stderr.write(f"[{datetime.now().strftime('%H:%M:%S')}] {args[0]} {args[1]} -> {args[2]}\n")

    def do_GET(self):
        parsed = urlparse(self.path)
        path = parsed.path
        query = parse_qs(parsed.query)

        user = get_current_user(self)

        # 1. Static Files (/css/*, /js/*, /images/*, favicon)
        if path.startswith("/css/") or path.startswith("/js/") or path.startswith("/images/") or path.endswith(".ico"):
            file_path = os.path.join(PUBLIC_DIR, path.lstrip("/"))
            if os.path.exists(file_path) and os.path.isfile(file_path):
                self.send_response(200)
                mime_type, _ = mimetypes.guess_type(file_path)
                self.send_header("Content-Type", mime_type or "text/plain")
                self.end_headers()
                with open(file_path, "rb") as f:
                    self.wfile.write(f.read())
                return
            else:
                self.send_error(404, "Static Asset Not Found")
                return

        # 2. REST API Endpoints
        if path == "/api/autocomplete" or path == "/v1/autocomplete":
            q = query.get("q", [""])[0].strip()
            suggestions = []
            if len(q) >= 2:
                with get_db() as conn:
                    cur = conn.cursor()
                    cur.execute("SELECT id, name, circuit, category FROM facilities WHERE name LIKE ? OR category LIKE ? LIMIT 3", (f"%{q}%", f"%{q}%"))
                    for r in cur.fetchall():
                        suggestions.append({
                            "type": f"Facility ({r['category']})",
                            "title": r['name'],
                            "circuit": r['circuit'],
                            "url": f"/routes?circuit={r['circuit']}"
                        })
                    cur.execute("SELECT id, name, circuit, centre_type FROM medical_centres WHERE name LIKE ? LIMIT 3", (f"%{q}%",))
                    for r in cur.fetchall():
                        suggestions.append({
                            "type": "Medical Post",
                            "title": r['name'],
                            "circuit": r['circuit'],
                            "url": f"/medical?circuit={r['circuit']}"
                        })
            self.send_json({"suggestions": suggestions})
            return

        if path == "/api/v1/facilities":
            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("SELECT * FROM facilities WHERE is_verified = 1")
                rows = [dict(r) for r in cur.fetchall()]
                self.send_json({"success": True, "data": rows})
            return

        if path == "/api/v1/medical":
            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("SELECT * FROM medical_centres")
                rows = [dict(r) for r in cur.fetchall()]
                self.send_json({"success": True, "data": rows})
            return

        if path == "/api/v1/accommodations":
            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("SELECT * FROM accommodations WHERE is_verified = 1")
                rows = [dict(r) for r in cur.fetchall()]
                self.send_json({"success": True, "data": rows})
            return

        # 3. CSV Exports (Admin Only)
        if path.startswith("/admin/export/"):
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login", "Unauthorized. Administrative login required.")
                return
            export_type = path.split("/")[-1]
            self.send_response(200)
            self.send_header("Content-Type", "text/csv")
            self.send_header("Content-Disposition", f'attachment; filename="yatri_{export_type}_{datetime.now().strftime("%Y%m%d_%H%M%S")}.csv"')
            self.end_headers()

            with get_db() as conn:
                cur = conn.cursor()
                if export_type == "requests":
                    self.wfile.write(b"ID,Tracking Code,Type,Priority,Pilgrim,Contact,Circuit,Location,Status,Date\n")
                    cur.execute("SELECT id, tracking_code, request_type, priority, pilgrim_name, contact_number, circuit, current_location, status, created_at FROM assistance_requests")
                    for r in cur.fetchall():
                        line = f'{r["id"]},"{r["tracking_code"]}","{r["request_type"]}","{r["priority"]}","{r["pilgrim_name"]}","{r["contact_number"]}","{r["circuit"]}","{r["current_location"]}","{r["status"]}","{r["created_at"]}"\n'
                        self.wfile.write(line.encode("utf-8"))
                elif export_type == "users":
                    self.wfile.write(b"ID,Role ID,Name,Email,Phone,Active,Joined\n")
                    cur.execute("SELECT id, role_id, name, email, phone, is_active, created_at FROM users")
                    for r in cur.fetchall():
                        line = f'{r["id"]},{r["role_id"]},"{r["name"]}","{r["email"]}","{r["phone"]}",{r["is_active"]},"{r["created_at"]}"\n'
                        self.wfile.write(line.encode("utf-8"))
                else:
                    self.wfile.write(b"ID,Pilgrim,Circuit,Rating,Aspect,Comment,Date\n")
                    cur.execute("SELECT id, pilgrim_name, circuit, rating, service_aspect, comment, created_at FROM feedback")
                    for r in cur.fetchall():
                        line = f'{r["id"]},"{r["pilgrim_name"]}","{r["circuit"]}",{r["rating"]},"{r["service_aspect"]}","{r["comment"]}","{r["created_at"]}"\n'
                        self.wfile.write(line.encode("utf-8"))
            return

        # 4. Authentication Pages
        if path == "/login":
            if user:
                target = "/admin" if user.get("role_id") == 1 else "/dashboard"
                self.redirect_with_cookie(target)
                return
            self.render_login_page()
            return

        if path == "/register":
            if user:
                self.redirect_with_cookie("/dashboard")
                return
            self.render_register_page()
            return

        if path == "/logout":
            cookie_header = self.headers.get("Cookie")
            if cookie_header:
                c = cookies.SimpleCookie(cookie_header)
                if "yatri_session" in c:
                    SESSIONS.pop(c["yatri_session"].value, None)
            self.send_response(302)
            self.send_header("Set-Cookie", "yatri_session=; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT")
            self.send_header("Location", "/?logged_out=1")
            self.end_headers()
            return

        if path == "/forgot-password":
            self.render_forgot_password_page()
            return

        # 5. Public Content Pages
        if path in ("/", "/home"):
            self.render_home_page(user)
            return

        if path == "/about":
            self.render_about_page(user)
            return

        if path == "/routes":
            circuit = query.get("circuit", ["Vaishno Devi"])[0]
            self.render_routes_page(user, circuit)
            return

        if path == "/medical":
            circuit = query.get("circuit", [""])[0]
            self.render_medical_page(user, circuit)
            return

        if path == "/accommodation":
            circuit = query.get("circuit", [""])[0]
            self.render_accommodation_page(user, circuit)
            return

        if path == "/weather":
            circuit = query.get("circuit", ["Vaishno Devi"])[0]
            self.render_weather_page(user, circuit)
            return

        if path == "/announcements":
            self.render_announcements_page(user)
            return

        if path == "/faqs":
            category = query.get("category", [""])[0]
            self.render_faqs_page(user, category)
            return

        if path == "/contact":
            self.render_contact_page(user)
            return

        if path == "/privacy":
            self.render_privacy_page(user)
            return

        if path == "/terms":
            self.render_terms_page(user)
            return

        if path == "/accessibility":
            self.render_accessibility_page(user)
            return

        if path == "/checklist":
            self.render_checklist_page(user)
            return

        if path == "/lost-found":
            self.render_lost_found_page(user)
            return

        if path == "/search":
            q = query.get("q", [""])[0].strip()
            cat = query.get("category", ["all"])[0]
            self.render_search_page(user, q, cat)
            return

        if path == "/track" or path.startswith("/track/"):
            code = path.split("/")[-1] if len(path.split("/")) > 2 else query.get("code", [""])[0]
            self.render_track_page(user, code)
            return

        if path == "/planner":
            self.render_planner_page(user)
            return

        if path.startswith("/planner/"):
            plan_id = path.split("/")[-1]
            self.render_saved_itinerary_page(user, plan_id)
            return

        # 6. User Protected Dashboard Pages
        if path == "/dashboard":
            if not user:
                self.redirect_with_cookie("/login")
                return
            self.render_user_dashboard(user)
            return

        if path == "/dashboard/profile":
            if not user:
                self.redirect_with_cookie("/login")
                return
            self.render_user_profile(user)
            return

        if path == "/dashboard/saved-places":
            if not user:
                self.redirect_with_cookie("/login")
                return
            self.render_user_saved_places(user)
            return

        if path == "/dashboard/requests":
            if not user:
                self.redirect_with_cookie("/login")
                return
            self.render_user_requests(user)
            return

        if path == "/dashboard/notifications":
            if not user:
                self.redirect_with_cookie("/login")
                return
            self.render_user_notifications(user)
            return

        # 7. Admin Protected Portal
        if path == "/admin":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_index(user)
            return

        if path == "/admin/users":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_users(user, query.get("search", [""])[0])
            return

        if path == "/admin/requests":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_requests(user, query.get("status", [""])[0])
            return

        if path == "/admin/medical":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_medical(user)
            return

        if path == "/admin/accommodations":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_accommodations(user)
            return

        if path == "/admin/announcements":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_announcements(user)
            return

        if path == "/admin/faqs":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_faqs(user)
            return

        if path == "/admin/feedback":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_feedback(user)
            return

        if path == "/admin/audit-logs":
            if not user or user.get("role_id") != 1:
                self.redirect_with_cookie("/login")
                return
            self.render_admin_audit_logs(user)
            return

        # Fallback 404
        self.send_html(f"{render_header('Page Not Found', user)}<div class='container' style='padding:5rem 0; text-align:center;'><h1>404 - Page Not Found</h1><p>The requested URL was not found on this server.</p><a href='/' class='btn btn-primary'>Return Home</a></div>{render_footer()}", 404)

    def do_POST(self):
        parsed = urlparse(self.path)
        path = parsed.path
        user = get_current_user(self)

        content_length = int(self.headers.get("Content-Length", 0))
        body = self.rfile.read(content_length)

        data = {}
        content_type = self.headers.get("Content-Type", "")
        if "application/json" in content_type:
            try:
                data = json.loads(body.decode("utf-8"))
            except Exception:
                data = {}
        else:
            data = parse_qs(body.decode("utf-8"))
            # Flatten single values
            data = {k: v[0] if len(v) == 1 else v for k, v in data.items()}

        # 1. Login Handling
        if path == "/login":
            email = data.get("email", "").strip()
            password = data.get("password", "")
            pw_hash = hash_pw(password)

            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("SELECT * FROM users WHERE email = ? AND is_active = 1", (email,))
                row = cur.fetchone()

                if row and (row["password"] == pw_hash or row["password"] == password):
                    session_id = secrets.token_hex(24)
                    SESSIONS[session_id] = row["id"]

                    # Log in audit
                    cur.execute("INSERT INTO audit_logs (user_id, user_email, action, model_type, model_id, details) VALUES (?, ?, ?, ?, ?, ?)",
                                (row["id"], email, "USER_LOGIN", "User", row["id"], "User signed in successfully"))
                    conn.commit()

                    target = "/admin" if row["role_id"] == 1 else "/dashboard"
                    self.send_response(302)
                    self.send_header("Set-Cookie", f"yatri_session={session_id}; Path=/; HttpOnly; SameSite=Lax")
                    self.send_header("Location", target)
                    self.end_headers()
                    return

            self.send_html(f"{render_header('Sign In Error')} <div class='container' style='padding:3rem 0; max-width:440px;'><div style='background:#fee2e2; padding:1.5rem; border-radius:12px; text-align:center;'><h3 style='color:#991b1b;'>Invalid Credentials</h3><p>The email or password entered does not match our records.</p><a href='/login' class='btn btn-primary'>Try Again</a></div></div> {render_footer()}", 401)
            return

        # 2. Registration Handling (Strictly Normal User Role ONLY)
        if path == "/register":
            name = data.get("name", "").strip()
            email = data.get("email", "").strip()
            phone = data.get("phone", "").strip()
            password = data.get("password", "")
            city = data.get("city", "").strip()
            state = data.get("state", "").strip()
            lang = data.get("preferred_language", "en")

            if not name or not email or not password or len(password) < 6:
                self.send_html(f"{render_header('Registration Error')} <div class='container' style='padding:3rem 0; max-width:440px;'><div style='background:#fee2e2; padding:1.5rem; border-radius:12px; text-align:center;'><h3 style='color:#991b1b;'>Validation Failed</h3><p>Password must be at least 6 characters and all required fields filled.</p><a href='/register' class='btn btn-primary'>Back</a></div></div> {render_footer()}", 422)
                return

            try:
                with get_db() as conn:
                    cur = conn.cursor()
                    cur.execute("INSERT INTO users (role_id, name, email, phone, password, preferred_language) VALUES (2, ?, ?, ?, ?, ?)",
                                (name, email, phone, hash_pw(password), lang))
                    user_id = cur.lastrowid
                    cur.execute("INSERT INTO profiles (user_id, city, state) VALUES (?, ?, ?)", (user_id, city, state))
                    cur.execute("INSERT INTO audit_logs (user_id, user_email, action, details) VALUES (?, ?, ?, ?)",
                                (user_id, email, "USER_REGISTRATION", "New pilgrim account created"))
                    conn.commit()

                    session_id = secrets.token_hex(24)
                    SESSIONS[session_id] = user_id

                    self.send_response(302)
                    self.send_header("Set-Cookie", f"yatri_session={session_id}; Path=/; HttpOnly; SameSite=Lax")
                    self.send_header("Location", "/dashboard")
                    self.end_headers()
                    return
            except sqlite3.IntegrityError:
                self.send_html(f"{render_header('Registration Error')} <div class='container' style='padding:3rem 0; max-width:440px;'><div style='background:#fee2e2; padding:1.5rem; border-radius:12px; text-align:center;'><h3 style='color:#991b1b;'>Email Already Registered</h3><p>An account with this email address already exists.</p><a href='/login' class='btn btn-primary'>Sign In Instead</a></div></div> {render_footer()}", 409)
                return

        # 3. Emergency SOS / Assistance Request Submission
        if path == "/assistance/submit" or path == "/v1/sos/submit":
            pilgrim_name = data.get("pilgrim_name", "").strip()
            contact_number = data.get("contact_number", "").strip()
            circuit = data.get("circuit", "Vaishno Devi")
            location = data.get("current_location", "").strip()
            description = data.get("description", "").strip()
            priority = data.get("priority", "critical")
            req_type = data.get("request_type", "emergency_sos")
            lat = data.get("latitude") or None
            lng = data.get("longitude") or None

            tracking_code = f"YS-REQ-{datetime.now().strftime('%Y%m')}-{secrets.randbelow(9000)+1000}"

            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("""
                    INSERT INTO assistance_requests (user_id, tracking_code, request_type, priority, pilgrim_name, contact_number, circuit, current_location, latitude, longitude, description, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
                """, (user["id"] if user else None, tracking_code, req_type, priority, pilgrim_name, contact_number, circuit, location, lat, lng, description))
                req_id = cur.lastrowid
                cur.execute("INSERT INTO request_updates (assistance_request_id, updated_by, status, comment) VALUES (?, 'System', 'pending', 'Emergency assistance request logged and assigned to coordinator desk.')", (req_id,))
                cur.execute("INSERT INTO audit_logs (user_id, action, model_type, model_id, details) VALUES (?, 'ASSISTANCE_REQUEST_CREATED', 'AssistanceRequest', ?, ?)",
                            (user["id"] if user else None, req_id, f"Tracking Code: {tracking_code}"))
                conn.commit()

            if "application/json" in content_type or self.headers.get("X-Requested-With") == "XMLHttpRequest":
                self.send_json({
                    "success": True,
                    "tracking_code": tracking_code,
                    "track_url": f"/track?code={tracking_code}",
                    "message": "Emergency assistance request successfully recorded."
                })
            else:
                self.send_response(302)
                self.send_header("Location", f"/track?code={tracking_code}")
                self.end_headers()
            return

        # 4. AI Chatbot API
        if path == "/api/ai/chat" or path == "/v1/ai/chat":
            msg = data.get("message", "").strip().lower()
            lang = data.get("language", "en")

            reply = ""
            action = None

            if any(k in msg for k in ("sos", "emergency", "accident", "chest pain", "hospital", "doctor", "आपातकाल", "मदद", "डॉक्टर")):
                reply = ("🚨 **आपातकालीन सहायता सूचना:**\n\nयदि आप चिकित्सा संकट में हैं, तो तुरंत:\n1. पोर्टल का **SOS बटन** दबाएं।\n2. आपातकालीन नंबर **112 / 108** पर कॉल करें।\n3. कटरा ट्रॉमा अस्पताल: `01991-232044` | महाकाल उज्जैन: `0734-2551444`।"
                         if lang == "hi" else
                         "🚨 **EMERGENCY MEDICAL ADVISORY:**\n\n1. Click the **SOS Button** on top to dispatch ground assistance.\n2. Dial Emergency **112** or Ambulance **108** immediately.\n3. Katra Base Hospital: `01991-232044` | Ujjain Emergency Post: `0734-2551444`.")
                action = "open_sos_modal"
            elif any(k in msg for k in ("rfid", "parchi", "slip", "registration", "पंजीकरण", "पर्ची")):
                reply = ("🎫 **RFID यात्रा पर्ची नियम:**\n\nप्रत्येक यात्री के लिए RFID कार्ड **निःशुल्क एवं अनिवार्य** है। कटरा स्टेशन या बस स्टैंड पर आधार कार्ड दिखाकर 5 मिनट में प्राप्त करें।"
                         if lang == "hi" else
                         "🎫 **RFID Yatra Slip Guidelines:**\n\nBiometric RFID cards are **100% free and mandatory**. Collect yours within 5 minutes at Katra Railway Station or Bus Stand with your physical Aadhaar card.")
            elif any(k in msg for k in ("battery", "elderly", "senior", "wheelchair", "विकलांग", "बुजुर्ग", "व्हीलचेयर")):
                reply = ("♿ **वरिष्ठ नागरिक सहायता:**\n\nसांझीछत से भवन तक बुजुर्गों (60+ वर्ष) के लिए बैटरी कार उपलब्ध है (प्रातः 07:00 से सायं 05:00)। वैध पहचान पत्र दिखाकर व्हीलचेयर अनुरोध किया जा सकता है।"
                         if lang == "hi" else
                         "♿ **Senior Citizen & Battery Car Rules:**\n\nElectric battery cars run between Sanjichhat and Bhawan (07:00 AM - 05:00 PM) prioritized for senior citizens (60+) and medically certified pilgrims.")
            else:
                reply = ("नमस्ते! मैं **यात्री सहायता** AI सहायक हूँ। आप मुझसे RFID कार्ड, बैटरी कार, ऑक्सीजन केंद्र या धर्मशालाओं के बारे में पूछ सकते हैं।"
                         if lang == "hi" else
                         "Namaste! I am your **Yatri Sahayata** AI Assistant. Ask me about RFID slips, battery cars, oxygen posts, or verified dharamshalas.")

            self.send_json({"success": True, "reply": reply, "action": action, "source": "verified_shrine_guidelines"})
            return

        # 5. Smart Planner Form / Generation API
        if path == "/planner/generate" or path == "/v1/planner/generate":
            circuit = data.get("circuit", "Vaishno Devi")
            days = max(1, min(7, int(data.get("duration_days", 3))))
            group = max(1, int(data.get("group_size", 2)))
            travel_mode = data.get("travel_mode", "train")
            accessibility = data.get("accessibility", "standard")
            budget = float(data.get("budget") or (days * 2000 * group))

            plan = {
                "title": f"Customized {circuit} Sacred Pilgrimage ({days} Days Plan)",
                "circuit": circuit,
                "duration_days": days,
                "group_size": group,
                "travel_mode": travel_mode,
                "estimated_budget_inr": budget,
                "days": []
            }

            for d in range(1, days + 1):
                plan["days"].append({
                    "day": d,
                    "theme": f"Day {d} Sacred Schedule",
                    "items": [
                        {
                            "time": "07:00 AM - 10:30 AM",
                            "title": "RFID Issuance & Base Camp Check-in",
                            "desc": f"Obtain free RFID biometric passes at {circuit} registration counter. Deposit heavy baggage.",
                            "location": f"{circuit} Base Camp",
                            "cost": 250 * group,
                            "tips": "Carry original physical Aadhaar cards."
                        },
                        {
                            "time": "11:30 AM - 04:00 PM",
                            "title": "Sacred Darshan & Temple Complex",
                            "desc": f"Proceed for sacred prayers at {circuit}. Senior citizen and wheelchair assistance available at Gate 2.",
                            "location": f"{circuit} Sanctum",
                            "cost": 400 * group,
                            "tips": "Leather belts and cell phones prohibited in sanctum."
                        },
                        {
                            "time": "05:30 PM - 08:30 PM",
                            "title": "Evening Aarti & Rest at Dharamshala",
                            "desc": "Witness evening lamp chanting and enjoy hot satvik meals at trust canteen.",
                            "location": "Pilgrim Rest Lounge",
                            "cost": 300 * group,
                            "tips": "Collect sanitized blankets for overnight rest."
                        }
                    ]
                })

            if "application/json" in content_type or self.headers.get("X-Requested-With") == "XMLHttpRequest":
                self.send_json({"success": True, "itinerary": plan})
            else:
                self.render_itinerary_view_page(user, plan, False)
            return

        # 6. Save Itinerary API
        if path == "/dashboard/planner/save":
            if not user:
                self.send_json({"success": False, "message": "Sign in required to save plan"}, 401)
                return
            title = data.get("title", "My Pilgrim Itinerary")
            circuit = data.get("destination_circuit", "Vaishno Devi")
            start_date = data.get("start_date", str(date.today()))
            days = int(data.get("duration_days", 3))
            group = int(data.get("group_size", 2))
            budget = float(data.get("estimated_budget_inr", 5000))
            items = data.get("items", [])

            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("""
                    INSERT INTO itineraries (user_id, title, destination_circuit, start_date, duration_days, group_size, estimated_budget_inr, is_public)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)
                """, (user["id"], title, circuit, start_date, days, group, budget))
                itn_id = cur.lastrowid

                for it in items:
                    cur.execute("""
                        INSERT INTO itinerary_items (itinerary_id, day_number, time_slot, activity_title, activity_description, location, estimated_cost, tips)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    """, (itn_id, it.get("day_number", 1), it.get("time_slot", "Morning"), it.get("activity_title", "Activity"), it.get("activity_description"), it.get("location", circuit), it.get("estimated_cost", 0), it.get("tips")))

                cur.execute("INSERT INTO audit_logs (user_id, action, model_type, model_id, details) VALUES (?, 'SAVE_ITINERARY', 'Itinerary', ?, ?)",
                            (user["id"], itn_id, f"Saved plan: {title}"))
                conn.commit()

            self.send_json({"success": True, "itinerary_id": itn_id, "redirect_url": f"/planner/{itn_id}"})
            return

        # 7. Admin Request Status Update
        if path.startswith("/admin/requests/") and path.endswith("/update"):
            if not user or user.get("role_id") != 1:
                self.send_json({"success": False, "message": "Unauthorized"}, 403)
                return
            req_id = int(path.split("/")[3])
            status = data.get("status", "in_progress")
            assigned_to = data.get("assigned_to", "").strip()
            comment = data.get("comment", "Status updated by admin").strip()

            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("UPDATE assistance_requests SET status = ?, assigned_to = ? WHERE id = ?", (status, assigned_to, req_id))
                cur.execute("INSERT INTO request_updates (assistance_request_id, updated_by, status, comment) VALUES (?, ?, ?, ?)",
                            (req_id, user["name"], status, comment))
                cur.execute("INSERT INTO audit_logs (user_id, action, model_type, model_id, details) VALUES (?, 'ADMIN_REQUEST_UPDATE', 'AssistanceRequest', ?, ?)",
                            (user["id"], req_id, f"Changed status to: {status}"))
                conn.commit()

            self.send_response(302)
            self.send_header("Location", "/admin/requests")
            self.end_headers()
            return

        # 8. User Profile Update
        if path == "/dashboard/profile":
            if not user:
                self.redirect_with_cookie("/login")
                return
            name = data.get("name", user["name"]).strip()
            phone = data.get("phone", user["phone"]).strip()
            em_name = data.get("emergency_contact_name", "").strip()
            em_phone = data.get("emergency_contact_phone", "").strip()
            blood = data.get("blood_group", "").strip()
            med = data.get("medical_conditions", "").strip()
            city = data.get("city", "").strip()
            state = data.get("state", "").strip()
            access = data.get("accessibility_needs", "").strip()
            lang = data.get("preferred_language", "en")

            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("UPDATE users SET name = ?, phone = ?, preferred_language = ? WHERE id = ?", (name, phone, lang, user["id"]))
                cur.execute("""
                    INSERT INTO profiles (user_id, emergency_contact_name, emergency_contact_phone, blood_group, medical_conditions, city, state, accessibility_needs)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ON CONFLICT(user_id) DO UPDATE SET
                        emergency_contact_name = excluded.emergency_contact_name,
                        emergency_contact_phone = excluded.emergency_contact_phone,
                        blood_group = excluded.blood_group,
                        medical_conditions = excluded.medical_conditions,
                        city = excluded.city,
                        state = excluded.state,
                        accessibility_needs = excluded.accessibility_needs
                """, (user["id"], em_name, em_phone, blood, med, city, state, access))
                conn.commit()

            self.send_response(302)
            self.send_header("Location", "/dashboard/profile?updated=1")
            self.end_headers()
            return

        # 9. Feedback Submission
        if path == "/dashboard/feedback":
            circuit = data.get("circuit", "Vaishno Devi")
            rating = int(data.get("rating", 5))
            aspect = data.get("service_aspect", "Overall Experience")
            comment = data.get("comment", "").strip()

            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("""
                    INSERT INTO feedback (user_id, pilgrim_name, email, circuit, rating, service_aspect, comment, is_moderated)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)
                """, (user["id"] if user else None, user["name"] if user else "Anonymous", user["email"] if user else None, circuit, rating, aspect, comment))
                conn.commit()

            self.send_response(302)
            self.send_header("Location", "/dashboard?feedback_success=1")
            self.end_headers()
            return

        # 10. Lost & Found Submission
        if path == "/lost-found":
            report_type = data.get("report_type", "lost")
            item_name = data.get("item_name", "").strip()
            category = data.get("category", "Other")
            circuit = data.get("circuit", "Vaishno Devi")
            location = data.get("incident_location", "").strip()
            incident_date = data.get("incident_date", str(date.today()))
            desc = data.get("description", "").strip()
            person = data.get("contact_person", "").strip()
            phone = data.get("contact_phone", "").strip()

            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("""
                    INSERT INTO lost_found_reports (user_id, report_type, item_name, category, circuit, incident_location, incident_date, description, contact_person, contact_phone, status, is_approved)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'reported', 1)
                """, (user["id"] if user else None, report_type, item_name, category, circuit, location, incident_date, desc, person, phone))
                conn.commit()

            self.send_response(302)
            self.send_header("Location", "/lost-found?submitted=1")
            self.end_headers()
            return

        self.send_error(404, "Unknown POST Route")

    # ==========================================
    # HTML Page Renderers
    # ==========================================
    def render_home_page(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM facilities WHERE is_verified = 1 LIMIT 6")
            facilities = cur.fetchall()
            cur.execute("SELECT * FROM medical_centres WHERE is_24x7 = 1 LIMIT 4")
            medical = cur.fetchall()
            cur.execute("SELECT * FROM accommodations WHERE is_verified = 1 LIMIT 4")
            accommodations = cur.fetchall()
            cur.execute("SELECT * FROM announcements WHERE is_active = 1 ORDER BY published_at DESC LIMIT 3")
            announcements = cur.fetchall()
            cur.execute("SELECT * FROM faqs WHERE is_active = 1 ORDER BY display_order LIMIT 5")
            faqs = cur.fetchall()

        fac_html = "".join([f'''
            <div class="yatri-card">
                <span class="card-badge badge-info">{f["category"]}</span>
                <h3 class="card-title">{f["name"]}</h3>
                <div class="card-meta">
                    <div>📍 {f["location"]} (<strong>{f["circuit"]}</strong>)</div>
                    <div>⏱️ Hours: {f["operating_hours"]} | 💰 {f["fee_type"]}</div>
                </div>
                <p class="card-desc">{f["description"]}</p>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#10b981; font-weight:700;">✓ Shrine Verified</span>
                    <a href="/routes?circuit={f['circuit']}" class="btn btn-sm btn-secondary">Locate on Map</a>
                </div>
            </div>
        ''' for f in facilities])

        med_html = "".join([f'''
            <div class="yatri-card" style="border-top:4px solid #ef4444;">
                <span class="card-badge badge-emergency">🏥 {m["centre_type"]}</span>
                <h3 class="card-title" style="color:#991b1b;">{m["name"]}</h3>
                <div class="card-meta">
                    <div>📍 {m["location"]}</div>
                    <div>👨‍⚕️ In-Charge: {m["doctor_in_charge"] or "Medical Officer"}</div>
                </div>
                <div style="background:#fef2f2; border:1px solid #fee2e2; border-radius:6px; padding:0.65rem; margin-bottom:1rem; font-weight:700; color:#b91c1c;">
                    📞 Emergency Helpline: <a href="tel:{m['helpline_number']}" style="color:#b91c1c;">{m['helpline_number']}</a>
                </div>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#b91c1c; font-weight:700;">🚨 24x7 Ready</span>
                    <a href="/medical" class="btn btn-sm btn-navy">View All</a>
                </div>
            </div>
        ''' for m in medical])

        faq_html = "".join([f'''
            <div class="faq-item">
                <button type="button" class="faq-question">
                    <span>{faq["question"]}</span>
                    <span class="toggle-icon">+</span>
                </button>
                <div class="faq-answer" style="display:none;">
                    <p>{faq["answer"]}</p>
                    {f'<div class="faq-hindi"><strong>हिन्दी अनुवाद:</strong><br>{faq["hindi_answer"]}</div>' if faq["hindi_answer"] else ''}
                </div>
            </div>
        ''' for faq in faqs])

        content = f'''
        {render_header("YATRI SAHAYATA – Smart Pilgrim Assistance Platform", user, "/")}
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container hero-grid">
                <div>
                    <div class="hero-tag"><span>🛡️</span> Verified Smart Pilgrim Guidance</div>
                    <h1 class="hero-title">Your Sacred Journey,<br><span class="gold-text">Our Responsibility.</span></h1>
                    <p class="hero-lead">Reliable route tracking, emergency first aid posts, RFID registration guidelines, wheelchair assistance, and intelligent itinerary planning for sacred yatris across India.</p>
                    <div style="display:flex; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
                        <a href="/planner" class="btn btn-primary" style="font-size:1.05rem; padding:0.85rem 1.75rem;"><span>✨</span> Plan Your Yatra Now</a>
                        <a href="/routes" class="btn btn-secondary" style="font-size:1.05rem; padding:0.85rem 1.5rem;"><span>🗺️</span> View Route Map</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item"><h3>24x7</h3><p>Emergency Medical & First Aid Directory</p></div>
                        <div class="stat-item"><h3>100% Free</h3><p>RFID Yatra Slip & Locker Info</p></div>
                        <div class="stat-item"><h3>7+</h3><p>Major Pilgrimage Circuits Covered</p></div>
                    </div>
                </div>
                <div>
                    <div class="search-card">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <h3 style="font-size:1.15rem; font-weight:800; color:#0a192f; margin:0;">🔍 Smart Pilgrim Search</h3>
                            <span style="font-size:0.75rem; background:#f1f5f9; padding:2px 8px; border-radius:12px; color:#475569;">Live Database</span>
                        </div>
                        <form action="/search" method="GET">
                            <div class="search-input-wrap">
                                <span class="search-icon">🔎</span>
                                <input type="text" id="mainSearchInput" name="q" class="search-input" placeholder="Search facilities, clinics, dharamshalas..." autocomplete="off">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                            <div id="searchAutocomplete" class="autocomplete-results"></div>
                        </form>
                        <div style="margin-top:1.25rem;">
                            <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Popular Circuits:</span>
                            <div class="circuit-pills">
                                <a href="/routes?circuit=Vaishno+Devi" class="circuit-pill">🚩 Vaishno Devi</a>
                                <a href="/routes?circuit=Ujjain+Mahakal" class="circuit-pill">🔱 Ujjain Mahakal</a>
                                <a href="/routes?circuit=Char+Dham" class="circuit-pill">🏔️ Char Dham</a>
                                <a href="/routes?circuit=Omkareshwar" class="circuit-pill">🌊 Omkareshwar</a>
                                <a href="/routes?circuit=Varanasi+Kashi" class="circuit-pill">🪔 Kashi Vishwanath</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Route Preview & Interactive Leaflet Map -->
        <section class="section" style="background:#ffffff;">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Interactive Navigation & POI Directory</span>
                    <h2 class="section-title">Verified Tracks & Milepost Guidance</h2>
                    <p class="section-desc">Accurate geographical locations for base camps, steep climbs, medical dispensaries, and rest stops across sacred pilgrimage paths.</p>
                </div>
                <div style="grid-template-columns:1fr 1fr; display:grid; gap:2rem; align-items:center;">
                    <div style="border-radius:16px; overflow:hidden; border:1px solid #cbd5e1; box-shadow:var(--shadow-md);">
                        <div id="yatriHomeMap" style="height:420px; width:100%;"></div>
                    </div>
                    <div>
                        <h3 style="color:#0a192f; font-size:1.35rem; font-weight:800; margin-bottom:1rem;">Mata Vaishno Devi Trek (13.5 KM)</h3>
                        <div class="route-timeline">
                            <div class="timeline-step">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content"><strong>1. Katra Base Camp (KM 0):</strong> RFID Slip Counter, locker rooms.</div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content"><strong>2. Banganga Checkpost (KM 1.5):</strong> Security gate & free water.</div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content"><strong>3. Ardhkuwari Midpoint (KM 6):</strong> Garbhjun Cave & oxygen booth.</div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content"><strong>4. Sanjichhat Helipad (KM 9.5):</strong> Battery car terminal for elders.</div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content"><strong>5. Holy Bhawan (KM 13.5):</strong> Holy cave sanctum & ropeway car.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Facilities -->
        <section class="section" style="background:#f8fafc;">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Ground Support Infrastructure</span>
                    <h2 class="section-title">Verified Rest Sheds & Cloak Rooms</h2>
                </div>
                <div class="cards-grid">{fac_html}</div>
            </div>
        </section>

        <!-- Medical -->
        <section class="section" style="background:#ffffff;">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag" style="color:#dc2626;">Immediate Healthcare Triage</span>
                    <h2 class="section-title">Emergency Medical & First Aid Posts</h2>
                </div>
                <div class="cards-grid">{med_html}</div>
            </div>
        </section>

        <!-- FAQs -->
        <section class="section" style="background:#f8fafc;">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Essential Yatra Knowledge</span>
                    <h2 class="section-title">Frequently Asked Questions</h2>
                </div>
                <div class="faq-wrap">{faq_html}</div>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', () => {{
                initPilgrimMap('yatriHomeMap', 'Vaishno Devi');
            }});
        </script>
        {render_footer()}
        '''
        self.send_html(content)

    def render_login_page(self):
        content = f'''
        {render_header("Sign In – Yatri Sahayata")}
        <section class="section" style="background:#f8fafc; padding:3.5rem 0;">
            <div class="container" style="max-width:480px;">
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-lg);">
                    <div style="text-align:center; margin-bottom:2rem;">
                        <div class="brand-logo-icon" style="margin:0 auto 1rem; width:3.5rem; height:3.5rem; font-size:1.75rem;">🕉️</div>
                        <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">Sign In to Your Account</h1>
                        <p style="color:#64748b; font-size:0.88rem; margin-top:0.25rem;">Access your saved itineraries, places & assistance cases</p>
                    </div>
                    <form action="/login" method="POST">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" id="loginEmail" name="email" class="form-control" placeholder="name@example.com" required autofocus>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div style="position:relative;">
                                <input type="password" id="loginPassword" name="password" class="form-control" placeholder="••••••••" required>
                                <button type="button" onclick="const p=document.getElementById('loginPassword'); p.type = p.type==='password'?'text':'password';" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer;">👁️</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; font-size:1rem; padding:0.75rem; margin-top:0.5rem;">Sign In</button>
                    </form>
                    <div style="margin-top:1.5rem; text-align:center; font-size:0.88rem; color:#64748b;">
                        New pilgrim? <a href="/register" style="color:#0284c7; font-weight:600;">Create Free Account</a>
                    </div>
                    <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px dashed #cbd5e1; font-size:0.78rem;">
                        <strong>🔑 Quick Demo Accounts (Click to Fill):</strong>
                        <div style="display:flex; flex-direction:column; gap:0.4rem; margin-top:0.5rem;">
                            <button type="button" onclick="document.getElementById('loginEmail').value='admin@yatrisahayata.org'; document.getElementById('loginPassword').value='Admin@123';" style="text-align:left; background:#f1f5f9; border:1px solid #cbd5e1; padding:4px 8px; border-radius:4px; cursor:pointer;">
                                🛡️ <strong>Admin:</strong> <code>admin@yatrisahayata.org</code> | <code>Admin@123</code>
                            </button>
                            <button type="button" onclick="document.getElementById('loginEmail').value='ramesh.sharma@example.com'; document.getElementById('loginPassword').value='Yatri@123';" style="text-align:left; background:#f1f5f9; border:1px solid #cbd5e1; padding:4px 8px; border-radius:4px; cursor:pointer;">
                                👤 <strong>Pilgrim User:</strong> <code>ramesh.sharma@example.com</code> | <code>Yatri@123</code>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_register_page(self):
        content = f'''
        {render_header("Register Pilgrim Account – Yatri Sahayata")}
        <section class="section" style="background:#f8fafc; padding:3rem 0;">
            <div class="container" style="max-width:540px;">
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-lg);">
                    <div style="text-align:center; margin-bottom:2rem;">
                        <div class="brand-logo-icon" style="margin:0 auto 1rem; width:3.5rem; height:3.5rem; font-size:1.75rem;">🕉️</div>
                        <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">Create Pilgrim Account</h1>
                        <p style="color:#64748b; font-size:0.88rem; margin-top:0.25rem;">Unlock personalized smart planning, emergency profile sync & requests</p>
                    </div>
                    <form action="/register" method="POST">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Ramesh Kumar Sharma" required>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" placeholder="ramesh@example.com" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mobile Phone *</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+91 98260 12345" required>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Password (Min 6 chars) *</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">City & State</label>
                                <input type="text" name="city" class="form-control" placeholder="Vidisha, MP">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; padding:0.75rem; margin-top:1rem;">Register Free Pilgrim Account</button>
                    </form>
                    <div style="margin-top:1.5rem; text-align:center; font-size:0.88rem;">
                        Already registered? <a href="/login" style="color:#0284c7; font-weight:600;">Sign In</a>
                    </div>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_about_page(self, user):
        content = f'''
        {render_header("About Yatri Sahayata – Academic Mission & Developer Credentials", user, "/about")}
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
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_routes_page(self, user, circuit):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM facilities WHERE circuit = ?", (circuit,))
            facilities = cur.fetchall()
            cur.execute("SELECT * FROM medical_centres WHERE circuit = ?", (circuit,))
            medical = cur.fetchall()

        fac_cards = "".join([f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:1.25rem;">
                <div style="display:flex; justify-content:space-between;">
                    <strong style="color:#0a192f;">{f["name"]}</strong>
                    <span class="card-badge badge-info">{f["category"]}</span>
                </div>
                <div style="font-size:0.85rem; color:#64748b; margin:4px 0;">📍 {f["location"]} | ⏱️ {f["operating_hours"]}</div>
                <p style="font-size:0.88rem; color:#475569; margin:0;">{f["description"]}</p>
            </div>
        ''' for f in facilities])

        med_cards = "".join([f'''
            <div style="background:#fff; border:1px solid #fee2e2; border-left:4px solid #ef4444; border-radius:8px; padding:1.25rem;">
                <div style="display:flex; justify-content:space-between;">
                    <strong style="color:#991b1b;">{m["name"]}</strong>
                    <span class="card-badge badge-emergency">{m["centre_type"]}</span>
                </div>
                <div style="font-size:0.85rem; color:#64748b; margin:4px 0;">📍 {m["location"]}</div>
                <div style="font-size:0.85rem; color:#b91c1c; font-weight:700;">📞 <a href="tel:{m['helpline_number']}" style="color:#b91c1c;">{m['helpline_number']}</a></div>
            </div>
        ''' for m in medical])

        content = f'''
        {render_header(f"Pilgrim Routes & Interactive Map – {circuit}", user, "/routes")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container">
                <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
                    <div>
                        <span class="section-tag">Interactive Navigation & POI Directory</span>
                        <h1 class="section-title" style="margin-bottom:0.25rem;">{circuit} Route Map & Guide</h1>
                    </div>
                    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <a href="/routes?circuit=Vaishno+Devi" class="btn btn-sm {'btn-primary' if circuit=='Vaishno Devi' else 'btn-secondary'}">Vaishno Devi</a>
                        <a href="/routes?circuit=Ujjain+Mahakal" class="btn btn-sm {'btn-primary' if circuit=='Ujjain Mahakal' else 'btn-secondary'}">Ujjain Mahakal</a>
                        <a href="/routes?circuit=Char+Dham" class="btn btn-sm {'btn-primary' if circuit=='Char Dham' else 'btn-secondary'}">Char Dham</a>
                        <a href="/routes?circuit=Omkareshwar" class="btn btn-sm {'btn-primary' if circuit=='Omkareshwar' else 'btn-secondary'}">Omkareshwar</a>
                    </div>
                </div>
                <div style="border-radius:12px; overflow:hidden; border:1px solid #cbd5e1; box-shadow:var(--shadow-md); margin-bottom:2.5rem;">
                    <div style="padding:0.75rem 1.25rem; background:#0a192f; color:#fff;">🗺️ <strong>Interactive OpenStreetMap (Leaflet Engine)</strong></div>
                    <div id="routeMapMain" style="height:500px; width:100%;"></div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                    <div><h3 style="color:#0a192f; margin-bottom:1rem;">Verified Facilities</h3><div style="display:flex; flex-direction:column; gap:1rem;">{fac_cards}</div></div>
                    <div><h3 style="color:#991b1b; margin-bottom:1rem;">Medical Posts</h3><div style="display:flex; flex-direction:column; gap:1rem;">{med_cards}</div></div>
                </div>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', () => {{
                initPilgrimMap('routeMapMain', "{circuit}");
            }});
        </script>
        {render_footer()}
        '''
        self.send_html(content)

    def render_medical_page(self, user, circuit):
        with get_db() as conn:
            cur = conn.cursor()
            if circuit:
                cur.execute("SELECT * FROM medical_centres WHERE circuit = ?", (circuit,))
            else:
                cur.execute("SELECT * FROM medical_centres")
            items = cur.fetchall()

        med_cards = "".join([f'''
            <div class="yatri-card" style="border-top:4px solid #ef4444;">
                <span class="card-badge badge-emergency">{m["centre_type"]}</span>
                <h3 class="card-title" style="color:#991b1b;">{m["name"]}</h3>
                <div class="card-meta">
                    <div>📍 {m["location"]} (<strong>{m["circuit"]}</strong>)</div>
                    <div>👨‍⚕️ In-Charge: {m["doctor_in_charge"] or "Duty Doctor"}</div>
                    <div>🩺 Equipment: {m["available_facilities"]}</div>
                </div>
                <div style="background:#fef2f2; border:1px solid #fee2e2; border-radius:8px; padding:0.75rem; text-align:center; margin:1rem 0;">
                    <div style="font-size:0.75rem; color:#64748b;">EMERGENCY HELPLINE</div>
                    <a href="tel:{m['helpline_number']}" style="font-size:1.25rem; font-weight:800; color:#b91c1c; text-decoration:none;">📞 {m['helpline_number']}</a>
                </div>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#b91c1c; font-weight:700;">{'24x7 Ready' if m['is_24x7'] else 'Regular Hours'}</span>
                    <a href="/routes?circuit={m['circuit']}" class="btn btn-sm btn-navy">Locate on Map</a>
                </div>
            </div>
        ''' for m in items])

        content = f'''
        {render_header("Emergency Medical Assistance Directory", user, "/medical")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container">
                <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:12px; padding:1.5rem; margin-bottom:2.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                    <div>
                        <span class="card-badge badge-emergency">🚨 CRITICAL MEDICAL DIRECTORY</span>
                        <h2 style="color:#991b1b; font-size:1.6rem; font-weight:800; margin:0.25rem 0;">24x7 High-Altitude Medical Emergency Triage</h2>
                        <p style="color:#9f1239; margin:0;">In acute crisis, dial 112 or 108 immediately. The units below are ground-verified posts.</p>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <a href="tel:112" class="btn btn-sos">📞 Dial 112</a>
                        <a href="tel:108" class="btn btn-navy">🚑 Dial 108</a>
                    </div>
                </div>
                <div class="cards-grid">{med_cards}</div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_accommodation_page(self, user, circuit):
        with get_db() as conn:
            cur = conn.cursor()
            if circuit:
                cur.execute("SELECT * FROM accommodations WHERE circuit = ?", (circuit,))
            else:
                cur.execute("SELECT * FROM accommodations")
            items = cur.fetchall()

        cards = "".join([f'''
            <div class="yatri-card">
                <div style="display:flex; justify-content:space-between;">
                    <span class="card-badge badge-gold">⭐ {a["rating"]} / 5.0</span>
                    <span class="card-badge badge-info">{a["type"]}</span>
                </div>
                <h3 class="card-title">{a["name"]}</h3>
                <div class="card-meta">
                    <div>📍 {a["address"]} (<strong>{a["circuit"]}</strong>)</div>
                    <div>💰 Tariff: <strong>{a["price_range"]}</strong></div>
                    <div>📞 Contact: <strong>{a["contact_phone"]}</strong></div>
                </div>
                <p class="card-desc"><strong>Amenities:</strong> {a["amenities"]}</p>
                <div class="card-footer">
                    <span style="font-size:0.8rem; color:#10b981; font-weight:700;">✓ Shrine Board Approved</span>
                    <a href="{a['booking_url'] or '#'}" target="_blank" class="btn btn-sm btn-primary">Official Portal &rarr;</a>
                </div>
            </div>
        ''' for a in items])

        content = f'''
        {render_header("Verified Accommodations & Dharamshalas", user, "/accommodation")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Economical & Trust Accommodations</span>
                    <h1 class="section-title">Verified Pilgrim Dharamshalas</h1>
                </div>
                <div class="cards-grid">{cards}</div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_weather_page(self, user, circuit):
        content = f'''
        {render_header("Pilgrim Track Weather & Microclimate Forecasts", user, "/weather")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">High-Altitude Meteorological Bulletin</span>
                    <h1 class="section-title">{circuit} Microclimate & Track Advisory</h1>
                </div>
                <div class="weather-card">
                    <div class="weather-header">
                        <div>
                            <span class="card-badge badge-gold">Active Bulletin: {circuit}</span>
                            <h2 style="color:#ffffff; font-size:1.8rem; font-weight:800; margin:0.4rem 0;">Trikuta Hills / Mountain Altitude Weather</h2>
                            <p style="color:#cbd5e1;">Light breeze, partly cloudy with isolated drizzle likelihood.</p>
                        </div>
                        <div style="text-align:right;">
                            <div class="weather-temp">16°C</div>
                            <div style="color:#fbbf24; font-size:0.85rem; font-weight:600;">Feels like 14°C (Night Wind)</div>
                        </div>
                    </div>
                    <div class="weather-grid">
                        <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                            <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Morning (06:00 - 11:00)</div>
                            <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">19°C</div>
                            <div style="font-size:0.8rem; color:#cbd5e1;">Clear & crisp. Best window for steep ascent.</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                            <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Afternoon (12:00 - 16:00)</div>
                            <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">22°C</div>
                            <div style="font-size:0.8rem; color:#cbd5e1;">Warm sunlight. Carry hydration bottles.</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                            <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Evening (17:00 - 21:00)</div>
                            <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">14°C</div>
                            <div style="font-size:0.8rem; color:#cbd5e1;">Chilly mountain drafts. Wear thermal layer.</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.06); padding:1rem; border-radius:8px;">
                            <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase;">Night (22:00 - 05:00)</div>
                            <div style="font-size:1.5rem; font-weight:700; color:#fbbf24; margin:0.35rem 0;">9°C</div>
                            <div style="font-size:0.8rem; color:#cbd5e1;">Cold winds at peak. Heavy woolens mandatory.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_announcements_page(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM announcements WHERE is_active = 1 ORDER BY published_at DESC")
            items = cur.fetchall()

        cards = "".join([f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-left:5px solid {'#ef4444' if a['priority']=='alert' else '#f59e0b'}; border-radius:10px; padding:1.5rem; margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span class="card-badge {'badge-emergency' if a['priority']=='alert' else 'badge-gold'}">{a['priority'].upper()}</span>
                        <strong>{a['title']}</strong>
                    </div>
                    <span style="font-size:0.75rem; color:#64748b;">{a['published_at']}</span>
                </div>
                <div style="font-size:0.85rem; color:#64748b; margin-bottom:0.5rem;">Circuit: <strong>{a['circuit']}</strong></div>
                <p style="font-size:0.92rem; color:#334155; margin:0;">{a['content']}</p>
            </div>
        ''' for a in items])

        content = f'''
        {render_header("Official Advisories & Notices", user, "/announcements")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <div class="section-header">
                    <span class="section-tag">Ground Bulletins</span>
                    <h1 class="section-title">Live Safety Advisories & Notices</h1>
                </div>
                <div>{cards}</div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_faqs_page(self, user, category):
        with get_db() as conn:
            cur = conn.cursor()
            if category:
                cur.execute("SELECT * FROM faqs WHERE category = ? ORDER BY display_order", (category,))
            else:
                cur.execute("SELECT * FROM faqs ORDER BY display_order")
            items = cur.fetchall()

        cards = "".join([f'''
            <div class="faq-item">
                <button type="button" class="faq-question">
                    <span>{f["question"]}</span>
                    <span class="toggle-icon">+</span>
                </button>
                <div class="faq-answer" style="display:none;">
                    <p>{f["answer"]}</p>
                    {f'<div class="faq-hindi"><strong>हिन्दी अनुवाद:</strong><br>{f["hindi_answer"]}</div>' if f["hindi_answer"] else ''}
                </div>
            </div>
        ''' for f in items])

        content = f'''
        {render_header("Frequently Asked Questions", user, "/faqs")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <div class="section-header">
                    <span class="section-tag">Help Desk Knowledge Base</span>
                    <h1 class="section-title">Frequently Asked Questions (FAQs)</h1>
                </div>
                <div class="faq-wrap">{cards}</div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_contact_page(self, user):
        content = f'''
        {render_header("Contact Coordination Desk", user, "/contact")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <div class="section-header">
                    <span class="section-tag">Communication & Feedback</span>
                    <h1 class="section-title">Contact Coordination Desk</h1>
                </div>
                <div style="background:#0a192f; color:#ffffff; border-radius:12px; padding:2rem;">
                    <h3 style="color:#fbbf24; margin-bottom:1rem;">Academic Developer Information</h3>
                    <p>
                        <strong>Lead Developer:</strong> Nidhi Chaudhary<br>
                        <strong>Enrollment Number:</strong> 0108CS241082<br>
                        <strong>College:</strong> Samrat Ashok Technological Institute (SATI), Vidisha, MP<br>
                        <strong>Deployment:</strong> <a href="https://nidhi-chaudhary.fwh.is/" target="_blank" style="color:#fbbf24; text-decoration:underline;">nidhi-chaudhary.fwh.is</a>
                    </p>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_privacy_page(self, user):
        content = f'''
        {render_header("Privacy Policy", user, "/privacy")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <h1 class="section-title">Privacy Policy</h1>
                <p>Location data is captured solely on explicit consent via the Emergency SOS button and used exclusively to route medical assistance. Cryptographic password hashing (SHA-256 / bcrypt) protects user credentials.</p>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_terms_page(self, user):
        content = f'''
        {render_header("Terms of Service", user, "/terms")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <h1 class="section-title">Terms of Service</h1>
                <p>YATRI SAHAYATA is an independent academic project developed by Nidhi Chaudhary (0108CS241082) at SATI Vidisha. It does not replace official Shrine Board registrations or emergency 112/108 calling.</p>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_accessibility_page(self, user):
        content = f'''
        {render_header("Accessibility Statement", user, "/accessibility")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <h1 class="section-title">Accessibility Statement</h1>
                <p>Designed in accordance with WCAG 2.1 AA contrast standards, keyboard navigation, and full bilingual Hindi & English language accessibility.</p>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_checklist_page(self, user):
        content = f'''
        {render_header("Pilgrim Travel Checklist", user, "/checklist")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h1 class="section-title">Pilgrim Travel Packing Checklist</h1>
                    <button onclick="window.print()" class="btn btn-navy">🖨️ Print / Save PDF</button>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1px solid #e2e8f0;">
                        <h3>🆔 Mandatory Identity</h3>
                        <p>• Original Govt Photo ID (Aadhaar / Passport)<br>• Photocopies for RFID Parchi<br>• Confirmed Train / Bus tickets</p>
                    </div>
                    <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1px solid #e2e8f0;">
                        <h3>💊 First Aid & Health</h3>
                        <p>• Personal prescription medicine<br>• Pain relief spray & blister plasters<br>• ORS electrolyte packets</p>
                    </div>
                    <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1px solid #e2e8f0;">
                        <h3>👟 Footwear & Gear</h3>
                        <p>• Broken-in athletic shoes with rubber grip<br>• Thick cotton socks<br>• Sturdy walking stick</p>
                    </div>
                    <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1px solid #e2e8f0;">
                        <h3>🧥 Warmth & Rain Gear</h3>
                        <p>• Waterproof rain poncho<br>• Windproof jacket / thermal inner<br>• Woolen cap for night winds</p>
                    </div>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_lost_found_page(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM lost_found_reports WHERE is_approved = 1 ORDER BY created_at DESC")
            items = cur.fetchall()

        cards = "".join([f'''
            <div class="yatri-card">
                <span class="card-badge {'badge-emergency' if r['report_type']=='lost' else 'badge-verified'}">{r['report_type'].upper()}</span>
                <h3 class="card-title">{r["item_name"]}</h3>
                <div class="card-meta">
                    <div>📍 Location: {r["incident_location"]} ({r["circuit"]})</div>
                    <div>👤 Contact: {r["contact_person"]}</div>
                </div>
                <p class="card-desc">{r["description"]}</p>
                <div class="card-footer">
                    <span>Status: {r["status"]}</span>
                    <a href="tel:{r['contact_phone']}" class="btn btn-sm btn-navy">📞 Call</a>
                </div>
            </div>
        ''' for r in items])

        content = f'''
        {render_header("Lost & Found Community Board", user, "/lost-found")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                    <h1 class="section-title">Lost & Found Community Board</h1>
                    <button onclick="document.getElementById('reportModal').style.display='flex'" class="btn btn-primary">➕ Report Lost or Found Item</button>
                </div>
                <div class="cards-grid">{cards}</div>
            </div>
        </section>
        <!-- Modal -->
        <div id="reportModal" class="modal-backdrop">
            <div class="modal-content">
                <button type="button" onclick="document.getElementById('reportModal').style.display='none'" class="modal-close">&times;</button>
                <h3 style="color:#0a192f; margin-bottom:1rem;">Report Lost or Found Article</h3>
                <form action="/lost-found" method="POST">
                    <div class="form-group">
                        <label class="form-label">Report Type</label>
                        <select name="report_type" class="form-control"><option value="lost">Lost</option><option value="found">Found</option></select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Article Name *</label>
                        <input type="text" name="item_name" class="form-control" placeholder="e.g. Brown leather wallet" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Circuit *</label>
                        <select name="circuit" class="form-control"><option value="Vaishno Devi">Vaishno Devi</option><option value="Ujjain Mahakal">Ujjain Mahakal</option></select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Location Milestone *</label>
                        <input type="text" name="incident_location" class="form-control" placeholder="e.g. Near Banganga Counter 2" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea name="description" rows="3" class="form-control" required></textarea>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <input type="text" name="contact_person" class="form-control" placeholder="Your Name" required>
                        <input type="tel" name="contact_phone" class="form-control" placeholder="Phone Number" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; margin-top:1rem;">Publish Report</button>
                </form>
            </div>
        </div>
        {render_footer()}
        '''
        self.send_html(content)

    def render_search_page(self, user, q, cat):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM facilities WHERE name LIKE ? OR category LIKE ? OR circuit LIKE ?", (f"%{q}%", f"%{q}%", f"%{q}%"))
            fac = cur.fetchall()
            cur.execute("SELECT * FROM medical_centres WHERE name LIKE ? OR circuit LIKE ?", (f"%{q}%", f"%{q}%"))
            med = cur.fetchall()
            cur.execute("SELECT * FROM faqs WHERE question LIKE ? OR answer LIKE ?", (f"%{q}%", f"%{q}%"))
            faqs = cur.fetchall()

        total = len(fac) + len(med) + len(faqs)

        results_html = ""
        if med:
            results_html += "<h3 style='color:#991b1b; margin-top:1.5rem;'>Medical Centres:</h3>" + "".join([f"<div style='background:#fef2f2; padding:0.75rem; border-radius:6px; margin-bottom:0.5rem;'><strong>{m['name']}</strong> ({m['circuit']}) - Phone: {m['helpline_number']}</div>" for m in med])
        if fac:
            results_html += "<h3 style='color:#0a192f; margin-top:1.5rem;'>Facilities:</h3>" + "".join([f"<div style='background:#f8fafc; padding:0.75rem; border-radius:6px; margin-bottom:0.5rem;'><strong>{f['name']}</strong> ({f['category']}) - {f['location']}</div>" for f in fac])
        if faqs:
            results_html += "<h3 style='color:#0a192f; margin-top:1.5rem;'>FAQs:</h3>" + "".join([f"<div style='background:#f8fafc; padding:0.75rem; border-radius:6px; margin-bottom:0.5rem;'><strong>{f['question']}</strong><p>{f['answer']}</p></div>" for f in faqs])

        if total == 0:
            results_html = "<div style='padding:3rem; text-align:center;'>No verified records found for your search. Try searching <em>Katra, Battery Car, Clinic</em>.</div>"

        content = f'''
        {render_header("Search Results – Yatri Sahayata", user, "/search")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:860px;">
                <h1 class="section-title">Results for "{q}" ({total} items found)</h1>
                {results_html}
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_track_page(self, user, code):
        req_data = None
        updates = []
        if code:
            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("SELECT * FROM assistance_requests WHERE tracking_code = ?", (code,))
                req_row = cur.fetchone()
                if req_row:
                    req_data = dict(req_row)
                    cur.execute("SELECT * FROM request_updates WHERE assistance_request_id = ? ORDER BY created_at DESC", (req_data["id"],))
                    updates = [dict(u) for u in cur.fetchall()]

        req_html = ""
        if req_data:
            timeline_html = "".join([f'''
                <div class="timeline-step">
                    <div class="timeline-dot" style="background:{'#10b981' if u['status']=='resolved' else '#0284c7'};"></div>
                    <div class="timeline-content">
                        <strong>{u['status'].upper()}</strong> - <span style="font-size:0.75rem; color:#64748b;">{u['created_at']}</span>
                        <p style="margin:4px 0 0;">{u['comment']}</p>
                        <span style="font-size:0.75rem; color:#64748b;">By: {u['updated_by']}</span>
                    </div>
                </div>
            ''' for u in updates])

            req_html = f'''
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:2rem; box-shadow:var(--shadow-md);">
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #f1f5f9; padding-bottom:1rem; margin-bottom:1rem;">
                    <div>
                        <span style="font-size:0.75rem; color:#64748b;">TRACKING CODE</span>
                        <h2 style="color:#0a192f; font-size:1.5rem; margin:0;">{req_data['tracking_code']}</h2>
                    </div>
                    <span class="card-badge {'badge-verified' if req_data['status']=='resolved' else 'badge-emergency'}">{req_data['status'].upper()}</span>
                </div>
                <div style="font-size:0.9rem; line-height:1.6; margin-bottom:1.5rem;">
                    <div><strong>Pilgrim:</strong> {req_data['pilgrim_name']} | <strong>Circuit:</strong> {req_data['circuit']}</div>
                    <div><strong>Milestone:</strong> {req_data['current_location']}</div>
                    <div><strong>Details:</strong> {req_data['description']}</div>
                    {f'<div style="color:#166534; font-weight:700; margin-top:4px;">🧑‍🤝‍🧑 Assigned Sevadar: {req_data["assigned_to"]}</div>' if req_data.get("assigned_to") else ''}
                </div>
                <h3>Timeline Updates</h3>
                <div class="route-timeline">{timeline_html}</div>
            </div>
            '''
        elif code:
            req_html = f"<div style='padding:2rem; background:#fee2e2; border-radius:8px; text-align:center;'>No record found with code '{code}'. Example format: YS-REQ-202611-1001.</div>"

        content = f'''
        {render_header("Track Assistance Request", user, "/track")}
        <section class="section" style="background:#ffffff; padding-top:2.5rem;">
            <div class="container" style="max-width:800px;">
                <h1 class="section-title">Track Assistance Request Status</h1>
                <form action="/track" method="GET" style="display:flex; gap:0.5rem; margin-bottom:2rem;">
                    <input type="text" name="code" class="form-control" placeholder="Enter Tracking Code (e.g. YS-REQ-202611-1001)" value="{code or ''}" required>
                    <button type="submit" class="btn btn-primary">Track</button>
                </form>
                {req_html}
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_planner_page(self, user):
        saved_html = ""
        if user:
            with get_db() as conn:
                cur = conn.cursor()
                cur.execute("SELECT * FROM itineraries WHERE user_id = ? ORDER BY created_at DESC", (user["id"],))
                saved_itns = cur.fetchall()
                if saved_itns:
                    saved_html = "<h3 style='margin-top:2rem;'>My Saved Plans</h3><div class='cards-grid'>" + "".join([f'''
                        <div class="yatri-card">
                            <span class="card-badge badge-gold">{it["duration_days"]} Days Plan</span>
                            <h4>{it["title"]}</h4>
                            <p>Circuit: {it["destination_circuit"]} | Group: {it["group_size"]}</p>
                            <a href="/planner/{it['id']}" class="btn btn-sm btn-primary">Open & Print</a>
                        </div>
                    ''' for it in saved_itns]) + "</div>"

        content = f'''
        {render_header("Smart Yatra Planner", user, "/planner")}
        <section class="section" style="background:#f8fafc; padding:2.5rem 0;">
            <div class="container" style="max-width:860px;">
                <div class="section-header">
                    <span class="section-tag">Algorithmic Travel Optimization</span>
                    <h1 class="section-title">Smart Yatra Planner</h1>
                </div>
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-md);">
                    <form action="/planner/generate" method="POST">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                            <div class="form-group">
                                <label class="form-label">Pilgrimage Circuit *</label>
                                <select name="circuit" class="form-control">
                                    <option value="Vaishno Devi">Shri Mata Vaishno Devi (Katra, J&K)</option>
                                    <option value="Ujjain Mahakal">Shri Mahakaleshwar & Omkareshwar (MP)</option>
                                    <option value="Char Dham">Char Dham (Kedarnath/Badrinath)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Duration *</label>
                                <select name="duration_days" class="form-control">
                                    <option value="1">1 Day</option>
                                    <option value="2">2 Days</option>
                                    <option value="3" selected>3 Days</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                            <div class="form-group">
                                <label class="form-label">Group Size *</label>
                                <input type="number" name="group_size" class="form-control" value="2" min="1" max="50" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Senior / Accessibility Needs</label>
                                <select name="accessibility" class="form-control">
                                    <option value="standard">Standard Trekking Pace</option>
                                    <option value="elderly">Elderly Friendly (Frequent Rest)</option>
                                    <option value="wheelchair">Wheelchair & Battery Car Priority</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; padding:0.85rem; font-size:1.05rem; margin-top:1rem;">
                            ✨ Generate Smart Itinerary
                        </button>
                    </form>
                </div>
                {saved_html}
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_itinerary_view_page(self, user, plan, is_saved):
        days_html = ""
        for d in plan["days"]:
            items_html = "".join([f'''
                <div class="timeline-step">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span style="font-size:0.75rem; background:#e0f2fe; color:#0284c7; padding:2px 6px; border-radius:4px; font-weight:700;">{it["time"]}</span>
                        <h4 style="margin:4px 0 2px;">{it["title"]}</h4>
                        <div style="font-size:0.8rem; color:#64748b;">📍 {it["location"]} | Est Cost: ₹{it["cost"]}</div>
                        <p style="font-size:0.88rem; color:#475569; margin:4px 0;">{it["desc"]}</p>
                    </div>
                </div>
            ''' for it in d["items"]])

            days_html += f'''
                <div style="margin-bottom:2rem;">
                    <div style="background:#0a192f; color:#fff; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1rem;">
                        <strong>Day {d["day"]} — {d["theme"]}</strong>
                    </div>
                    <div class="route-timeline">{items_html}</div>
                </div>
            '''

        save_btn = f'''
            <button onclick='savePlan({json.dumps(plan)})' class="btn btn-sm btn-primary">💾 Save to My Profile</button>
        ''' if user and not is_saved else ('' if is_saved else '<a href="/login" class="btn btn-sm btn-primary">Sign in to Save</a>')

        content = f'''
        {render_header("Itinerary View", user, "/planner")}
        <section class="section" style="background:#f8fafc; padding:2.5rem 0;">
            <div class="container" style="max-width:860px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <a href="/planner" class="btn btn-sm btn-secondary">&larr; Back</a>
                    <div style="display:flex; gap:0.5rem;">
                        <button onclick="window.print()" class="btn btn-sm btn-navy">🖨️ Print / Save PDF</button>
                        {save_btn}
                    </div>
                </div>
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-md);">
                    <div style="border-bottom:2px solid #f1f5f9; padding-bottom:1rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span class="card-badge badge-gold">Verified Algorithmic Plan</span>
                            <h2 style="margin:0.25rem 0;">{plan["title"]}</h2>
                            <span style="color:#64748b; font-size:0.85rem;">Group: {plan["group_size"]} Yatris | {plan["duration_days"]} Days</span>
                        </div>
                        <div style="text-align:right;">
                            <span style="font-size:0.75rem; color:#64748b;">ESTIMATED BUDGET</span>
                            <div style="font-size:1.8rem; font-weight:800; color:#10b981;">₹{int(plan["estimated_budget_inr"]):,}*</div>
                        </div>
                    </div>
                    {days_html}
                </div>
            </div>
        </section>
        <script>
            function savePlan(planData) {{
                const flatItems = [];
                planData.days.forEach(d => {{
                    d.items.forEach(it => {{
                        flatItems.push({{
                            day_number: d.day,
                            time_slot: it.time,
                            activity_title: it.title,
                            activity_description: it.desc,
                            location: it.location,
                            estimated_cost: it.cost || 0
                        }});
                    }});
                }});
                fetch('/dashboard/planner/save', {{
                    method: 'POST',
                    headers: {{'Content-Type': 'application/json'}},
                    body: JSON.stringify({{
                        title: planData.title,
                        destination_circuit: planData.circuit,
                        duration_days: planData.duration_days,
                        group_size: planData.group_size,
                        estimated_budget_inr: planData.estimated_budget_inr,
                        items: flatItems
                    }})
                }}).then(res => res.json()).then(data => {{
                    if(data.success) window.location.href = data.redirect_url;
                }});
            }}
        </script>
        {render_footer()}
        '''
        self.send_html(content)

    def render_saved_itinerary_page(self, user, plan_id):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM itineraries WHERE id = ?", (plan_id,))
            itn = cur.fetchone()
            if not itn:
                self.send_error(404, "Itinerary Not Found")
                return
            cur.execute("SELECT * FROM itinerary_items WHERE itinerary_id = ? ORDER BY day_number, id", (plan_id,))
            items = cur.fetchall()

        plan = {
            "title": itn["title"],
            "circuit": itn["destination_circuit"],
            "duration_days": itn["duration_days"],
            "group_size": itn["group_size"],
            "estimated_budget_inr": itn["estimated_budget_inr"],
            "days": []
        }

        days_dict = {}
        for it in items:
            d = it["day_number"]
            if d not in days_dict:
                days_dict[d] = []
            days_dict[d].append({
                "time": it["time_slot"],
                "title": it["activity_title"],
                "desc": it["activity_description"],
                "location": it["location"],
                "cost": it["estimated_cost"]
            })

        for d, itms in sorted(days_dict.items()):
            plan["days"].append({
                "day": d,
                "theme": f"Day {d} Sacred Schedule",
                "items": itms
            })

        self.render_itinerary_view_page(user, plan, True)

    def render_user_dashboard(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT COUNT(*) FROM itineraries WHERE user_id = ?", (user["id"],))
            itn_count = cur.fetchone()[0]
            cur.execute("SELECT * FROM assistance_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 5", (user["id"],))
            requests = cur.fetchall()

        req_html = "".join([f'''
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:0.85rem; margin-bottom:0.5rem; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <strong>{r['tracking_code']}</strong> - <span class="card-badge {'badge-verified' if r['status']=='resolved' else 'badge-emergency'}">{r['status'].upper()}</span>
                    <div style="font-size:0.8rem; color:#64748b;">{r['circuit']} | {r['current_location']}</div>
                </div>
                <a href="/track?code={r['tracking_code']}" class="btn btn-sm btn-secondary">Track</a>
            </div>
        ''' for r in requests]) or "<p style='color:#64748b; font-size:0.85rem;'>No assistance requests logged.</p>"

        content = f'''
        {render_header("Pilgrim Dashboard", user, "/dashboard")}
        <section class="section" style="background:#f8fafc; padding:2.5rem 0;">
            <div class="container">
                <div style="background:linear-gradient(135deg, #0a192f, #1e293b); color:#fff; padding:2rem; border-radius:16px; margin-bottom:2rem; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <span class="card-badge badge-gold">PILGRIM MEMBER</span>
                        <h1 style="font-size:1.8rem; margin:0.25rem 0;">{user['name']}</h1>
                        <span style="color:#cbd5e1; font-size:0.85rem;">{user['email']} | {user['phone']}</span>
                    </div>
                    <div style="display:flex; gap:0.5rem;">
                        <a href="/planner" class="btn btn-primary">✨ New Yatra Plan</a>
                        <a href="/dashboard/profile" class="btn btn-secondary">Emergency Profile</a>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1.2fr 0.8fr; gap:2rem;">
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                        <h3>🚨 My Assistance Requests</h3>
                        <div style="margin-top:1rem;">{req_html}</div>
                    </div>
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                        <h3>⭐ Submit Pilgrim Review</h3>
                        <form action="/dashboard/feedback" method="POST" style="margin-top:1rem;">
                            <div class="form-group">
                                <label class="form-label">Circuit</label>
                                <select name="circuit" class="form-control"><option value="Vaishno Devi">Vaishno Devi</option><option value="Ujjain Mahakal">Ujjain Mahakal</option></select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-control"><option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option><option value="4">⭐⭐⭐⭐ (4 Stars)</option></select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Comments</label>
                                <textarea name="comment" rows="3" class="form-control" placeholder="Share your experience..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%;">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_user_profile(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM profiles WHERE user_id = ?", (user["id"],))
            prof = cur.fetchone()

        em_name = prof["emergency_contact_name"] if prof and prof["emergency_contact_name"] else ""
        em_phone = prof["emergency_contact_phone"] if prof and prof["emergency_contact_phone"] else ""
        city = prof["city"] if prof and prof["city"] else ""
        state = prof["state"] if prof and prof["state"] else ""
        med = prof["medical_conditions"] if prof and prof["medical_conditions"] else ""

        content = f'''
        {render_header("Pilgrim Profile & Emergency Preferences", user, "/dashboard/profile")}
        <section class="section" style="background:#f8fafc; padding:2.5rem 0;">
            <div class="container" style="max-width:760px;">
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem;">
                    <h1 class="section-title">Pilgrim Profile & Emergency Details</h1>
                    <form action="/dashboard/profile" method="POST">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{user['name']}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mobile Phone</label>
                                <input type="tel" name="phone" class="form-control" value="{user['phone'] or ''}" required>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                            <div class="form-group">
                                <label class="form-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{em_name}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Emergency Contact Phone</label>
                                <input type="tel" name="emergency_contact_phone" class="form-control" value="{em_phone}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Medical Conditions / Daily Meds</label>
                            <textarea name="medical_conditions" rows="2" class="form-control">{med}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">Save Changes</button>
                    </form>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_user_saved_places(self, user):
        content = f'''
        {render_header("My Saved Places", user, "/dashboard/saved-places")}
        <section class="section" style="background:#f8fafc; padding:2.5rem 0;">
            <div class="container" style="max-width:800px;">
                <h1 class="section-title">My Saved Places & Facilities</h1>
                <p>Bookmark your favorite cloakrooms and medical posts from the route map for quick offline reference.</p>
                <a href="/routes" class="btn btn-primary">Browse Route POIs</a>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_user_requests(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM assistance_requests WHERE user_id = ? ORDER BY created_at DESC", (user["id"],))
            items = cur.fetchall()

        cards = "".join([f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem; margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between;">
                    <strong>{r['tracking_code']}</strong>
                    <span class="card-badge {'badge-verified' if r['status']=='resolved' else 'badge-emergency'}">{r['status'].upper()}</span>
                </div>
                <p style="margin:8px 0;">{r['description']}</p>
                <div style="font-size:0.8rem; color:#64748b;">Location: {r['current_location']} ({r['circuit']})</div>
                <a href="/track?code={r['tracking_code']}" class="btn btn-sm btn-secondary" style="margin-top:0.5rem;">Track Case</a>
            </div>
        ''' for r in items]) or "<p>No assistance requests logged yet.</p>"

        content = f'''
        {render_header("My Assistance Requests", user, "/dashboard/requests")}
        <section class="section" style="background:#f8fafc; padding:2.5rem 0;">
            <div class="container" style="max-width:860px;">
                <h1 class="section-title">My Assistance Requests</h1>
                {cards}
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_user_notifications(self, user):
        content = f'''
        {render_header("Notifications", user, "/dashboard/notifications")}
        <section class="section" style="background:#f8fafc; padding:2.5rem 0;">
            <div class="container" style="max-width:800px;">
                <h1 class="section-title">Notifications & Operational Messages</h1>
                <div style="background:#fff; padding:1.5rem; border-radius:12px; border:1px solid #e2e8f0;">
                    <strong>Welcome to Yatri Sahayata!</strong>
                    <p style="margin:4px 0 0; color:#64748b;">Your account has been configured. In case of emergency on the track, tap the SOS button anytime.</p>
                </div>
            </div>
        </section>
        {render_footer()}
        '''
        self.send_html(content)

    def render_admin_index(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT COUNT(*) FROM users")
            total_users = cur.fetchone()[0]
            cur.execute("SELECT COUNT(*) FROM assistance_requests")
            total_reqs = cur.fetchone()[0]
            cur.execute("SELECT COUNT(*) FROM assistance_requests WHERE status = 'pending'")
            pending_reqs = cur.fetchone()[0]
            cur.execute("SELECT * FROM assistance_requests ORDER BY created_at DESC LIMIT 5")
            recent_reqs = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td><strong>{r['tracking_code']}</strong></td>
                <td><span class="card-badge {'badge-emergency' if r['priority']=='critical' else 'badge-gold'}">{r['priority'].upper()}</span></td>
                <td>{r['pilgrim_name']}</td>
                <td>{r['circuit']}</td>
                <td><span class="card-badge {'badge-verified' if r['status']=='resolved' else 'badge-emergency'}">{r['status'].upper()}</span></td>
                <td><a href="/admin/requests" class="btn btn-sm btn-primary" style="padding:2px 8px; font-size:0.75rem;">Manage</a></td>
            </tr>
        ''' for r in recent_reqs])

        inner_html = f'''
            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1.25rem; margin-bottom:2rem;">
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:700;">TOTAL USERS</div>
                    <div style="font-size:2rem; font-weight:800; color:#0a192f;">{total_users}</div>
                </div>
                <div style="background:#fff; border:1px solid #fee2e2; border-left:4px solid #ef4444; border-radius:12px; padding:1.25rem;">
                    <div style="font-size:0.75rem; color:#991b1b; font-weight:700;">PENDING REQUESTS</div>
                    <div style="font-size:2rem; font-weight:800; color:#b91c1c;">{pending_reqs}</div>
                </div>
                <div style="background:#fff; border:1px solid #d1fae5; border-left:4px solid #10b981; border-radius:12px; padding:1.25rem;">
                    <div style="font-size:0.75rem; color:#065f46; font-weight:700;">TOTAL REQUESTS</div>
                    <div style="font-size:2rem; font-weight:800; color:#059669;">{total_reqs}</div>
                </div>
            </div>
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <h3>🚨 Recent Assistance Alerts</h3>
                <div class="table-responsive" style="margin-top:1rem;">
                    <table class="yatri-table">
                        <thead><tr><th>Code</th><th>Priority</th><th>Pilgrim</th><th>Circuit</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        '''
        content = self.render_admin_scaffold(user, "Administrative Overview", inner_html)
        self.send_html(content)

    def render_admin_users(self, user, search=""):
        with get_db() as conn:
            cur = conn.cursor()
            if search:
                cur.execute("SELECT u.*, r.display_name as role_title FROM users u JOIN roles r ON u.role_id = r.id WHERE u.name LIKE ? OR u.email LIKE ?", (f"%{search}%", f"%{search}%"))
            else:
                cur.execute("SELECT u.*, r.display_name as role_title FROM users u JOIN roles r ON u.role_id = r.id")
            users = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td>{u['id']}</td>
                <td><strong>{u['name']}</strong></td>
                <td>{u['email']}</td>
                <td>{u['phone'] or '—'}</td>
                <td><span class="card-badge badge-info">{u['role_title']}</span></td>
                <td><span class="card-badge {'badge-verified' if u['is_active'] else 'badge-emergency'}">{'ACTIVE' if u['is_active'] else 'INACTIVE'}</span></td>
            </tr>
        ''' for u in users])

        content = self.render_admin_scaffold(user, "User & Role Management", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        ''')
        self.send_html(content)

    def render_admin_requests(self, user, status=""):
        with get_db() as conn:
            cur = conn.cursor()
            if status:
                cur.execute("SELECT * FROM assistance_requests WHERE status = ? ORDER BY created_at DESC", (status,))
            else:
                cur.execute("SELECT * FROM assistance_requests ORDER BY created_at DESC")
            reqs = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td><strong>{r['tracking_code']}</strong></td>
                <td><span class="card-badge {'badge-emergency' if r['priority']=='critical' else 'badge-gold'}">{r['priority'].upper()}</span></td>
                <td>{r['pilgrim_name']}<br><span style="font-size:0.75rem; color:#64748b;">{r['contact_number']}</span></td>
                <td>{r['circuit']}<br><span style="font-size:0.75rem; color:#64748b;">{r['current_location']}</span></td>
                <td><span class="card-badge {'badge-verified' if r['status']=='resolved' else 'badge-emergency'}">{r['status'].upper()}</span></td>
                <td>{r['assigned_to'] or '—'}</td>
                <td>
                    <button onclick="openModal({r['id']}, '{r['tracking_code']}')" class="btn btn-sm btn-primary" style="padding:2px 8px; font-size:0.75rem;">Update Status</button>
                </td>
            </tr>
        ''' for r in reqs])

        modal_html = '''
        <div id="statusModal" class="modal-backdrop">
            <div class="modal-content">
                <button type="button" onclick="document.getElementById('statusModal').style.display='none'" class="modal-close">&times;</button>
                <h3>Update Status: <span id="targetCode"></span></h3>
                <form id="updateForm" method="POST">
                    <div class="form-group">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="acknowledged">Acknowledged</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assign To</label>
                        <input type="text" name="assigned_to" class="form-control" placeholder="e.g. Amit Verma (Volunteer)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Resolution Comment</label>
                        <textarea name="comment" rows="3" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Save Update</button>
                </form>
            </div>
        </div>
        <script>
            function openModal(id, code) {
                document.getElementById('targetCode').innerText = code;
                document.getElementById('updateForm').action = '/admin/requests/' + id + '/update';
                document.getElementById('statusModal').style.display = 'flex';
            }
        </script>
        '''

        content = self.render_admin_scaffold(user, "Assistance Request Triage", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>Code</th><th>Priority</th><th>Pilgrim</th><th>Location</th><th>Status</th><th>Assigned</th><th>Action</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
            {modal_html}
        ''')
        self.send_html(content)

    def render_admin_medical(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM medical_centres")
            items = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td>{m['id']}</td>
                <td><strong style="color:#991b1b;">{m['name']}</strong></td>
                <td>{m['centre_type']}</td>
                <td>{m['circuit']}</td>
                <td>{m['helpline_number']}</td>
            </tr>
        ''' for m in items])

        content = self.render_admin_scaffold(user, "Medical Directory Management", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Circuit</th><th>Helpline</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        ''')
        self.send_html(content)

    def render_admin_accommodations(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM accommodations")
            items = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td>{a['id']}</td>
                <td><strong>{a['name']}</strong></td>
                <td>{a['type']}</td>
                <td>{a['circuit']}</td>
                <td>{a['price_range']}</td>
                <td>⭐ {a['rating']}</td>
            </tr>
        ''' for a in items])

        content = self.render_admin_scaffold(user, "Accommodation Directory", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Circuit</th><th>Tariff</th><th>Rating</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        ''')
        self.send_html(content)

    def render_admin_announcements(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM announcements ORDER BY published_at DESC")
            items = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td>{a['id']}</td>
                <td><span class="card-badge {'badge-emergency' if a['priority']=='alert' else 'badge-gold'}">{a['priority'].upper()}</span></td>
                <td>{a['circuit']}</td>
                <td><strong>{a['title']}</strong></td>
                <td>{a['published_at']}</td>
            </tr>
        ''' for a in items])

        content = self.render_admin_scaffold(user, "Safety Advisories", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>ID</th><th>Priority</th><th>Circuit</th><th>Title</th><th>Date</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        ''')
        self.send_html(content)

    def render_admin_faqs(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM faqs ORDER BY display_order")
            items = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td>{f['display_order']}</td>
                <td>{f['category']}</td>
                <td><strong>{f['question']}</strong></td>
            </tr>
        ''' for f in items])

        content = self.render_admin_scaffold(user, "FAQ Knowledge Base", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>Order</th><th>Category</th><th>Question</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        ''')
        self.send_html(content)

    def render_admin_feedback(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM feedback ORDER BY created_at DESC")
            items = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td>{f['id']}</td>
                <td>{f['pilgrim_name']}</td>
                <td>{f['circuit']}</td>
                <td>{'⭐'*f['rating']}</td>
                <td>{f['comment']}</td>
            </tr>
        ''' for f in items])

        content = self.render_admin_scaffold(user, "Pilgrim Feedback Moderation", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>ID</th><th>Pilgrim</th><th>Circuit</th><th>Rating</th><th>Comment</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        ''')
        self.send_html(content)

    def render_admin_audit_logs(self, user):
        with get_db() as conn:
            cur = conn.cursor()
            cur.execute("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 50")
            items = cur.fetchall()

        rows = "".join([f'''
            <tr>
                <td>{l['created_at']}</td>
                <td><strong>{l['action']}</strong></td>
                <td>{l['user_email'] or 'System'}</td>
                <td>{l['details']}</td>
            </tr>
        ''' for l in items])

        content = self.render_admin_scaffold(user, "Security Audit Trail", f'''
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem;">
                <div class="table-responsive">
                    <table class="yatri-table">
                        <thead><tr><th>Timestamp</th><th>Action</th><th>User</th><th>Details</th></tr></thead>
                        <tbody>{rows}</tbody>
                    </table>
                </div>
            </div>
        ''')
        self.send_html(content)

    def render_admin_scaffold(self, user, title, inner_content):
        return f'''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title} – Yatri Sahayata Admin</title>
    <link rel="stylesheet" href="/css/yatri-main.css">
    <style>
        .admin-layout {{ display:grid; grid-template-columns:250px 1fr; min-height:100vh; }}
        .admin-sidebar {{ background:#0a192f; color:#fff; padding:1.5rem 1rem; }}
        .admin-nav {{ list-style:none; display:flex; flex-direction:column; gap:0.4rem; margin-top:1.5rem; }}
        .admin-nav a {{ display:flex; align-items:center; gap:0.5rem; color:#cbd5e1; text-decoration:none; padding:0.6rem 0.8rem; border-radius:6px; font-size:0.88rem; }}
        .admin-nav a:hover {{ background:#1e293b; color:#fbbf24; }}
        .admin-main {{ background:#f8fafc; padding:2rem; }}
        .table-responsive {{ width:100%; overflow-x:auto; background:#fff; border-radius:10px; border:1px solid #e2e8f0; }}
        table.yatri-table {{ width:100%; border-collapse:collapse; font-size:0.88rem; text-align:left; }}
        table.yatri-table th {{ background:#0f172a; color:#fff; padding:0.8rem 1rem; }}
        table.yatri-table td {{ padding:0.8rem 1rem; border-bottom:1px solid #f1f5f9; }}
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="brand-wrap">
                <div class="brand-logo-icon">🕉️</div>
                <div class="brand-text">
                    <h2 style="font-size:1.1rem; color:#fff; margin:0;">YATRI CONTROL</h2>
                    <span style="color:#fbbf24; font-size:0.7rem;">Admin Desk</span>
                </div>
            </div>
            <ul class="admin-nav">
                <li><a href="/admin">📊 Overview</a></li>
                <li><a href="/admin/requests">🚨 Requests Triage</a></li>
                <li><a href="/admin/users">👥 Users & Roles</a></li>
                <li><a href="/admin/medical">🏥 Medical Directory</a></li>
                <li><a href="/admin/accommodations">🏨 Accommodations</a></li>
                <li><a href="/admin/announcements">📢 Advisories</a></li>
                <li><a href="/admin/faqs">❓ FAQs</a></li>
                <li><a href="/admin/feedback">⭐ Feedback</a></li>
                <li><a href="/admin/audit-logs">📜 Audit Logs</a></li>
            </ul>
            <div style="margin-top:2rem; padding-top:1rem; border-top:1px solid #1e293b;">
                <div style="font-size:0.75rem; color:#94a3b8; margin-bottom:0.5rem;">Logged: {user['name']}</div>
                <div style="display:flex; gap:0.5rem;">
                    <a href="/" class="btn btn-sm btn-secondary" style="font-size:0.75rem;">Public Portal</a>
                    <a href="/logout" class="btn btn-sm btn-navy" style="font-size:0.75rem; background:#dc2626;">Logout</a>
                </div>
            </div>
        </aside>
        <main class="admin-main">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">{title}</h1>
                    <span style="font-size:0.85rem; color:#64748b;">Nidhi Chaudhary (0108CS241082) | SATI Vidisha</span>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <a href="/admin/export/requests" class="btn btn-sm btn-navy">📥 Export Requests CSV</a>
                    <a href="/admin/export/users" class="btn btn-sm btn-secondary">📥 Export Users CSV</a>
                </div>
            </div>
            {inner_content}
        </main>
    </div>
</body>
</html>
'''

    # Response Helpers
    def send_html(self, html_str: str, code: int = 200):
        body = html_str.encode("utf-8")
        self.send_response(code)
        self.send_header("Content-Type", "text/html; charset=utf-8")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def send_json(self, data: dict, code: int = 200):
        body = json.dumps(data).encode("utf-8")
        self.send_response(code)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)

    def redirect_with_cookie(self, location: str, error_msg: str = None):
        self.send_response(302)
        self.send_header("Location", location)
        self.end_headers()

def main():
    if not os.path.exists(DB_PATH):
        print("Database not found! Initializing database first...")
        from database.init_db import init_database
        init_database()

    server_address = ('', PORT)
    httpd = HTTPServer(server_address, YatriRequestHandler)
    print("=" * 65)
    print("🕉️  YATRI SAHAYATA – Smart Pilgrim Assistance Platform")
    print("   Developer: Nidhi Chaudhary | Enrollment: 0108CS241082")
    print("   College: Samrat Ashok Technological Institute, Vidisha, MP")
    print(f"🚀 Server Running at: http://localhost:{PORT}/")
    print("=" * 65)
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nServer shutting down gracefully.")
        httpd.server_close()

if __name__ == "__main__":
    main()
