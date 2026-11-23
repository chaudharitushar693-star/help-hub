# YATRI SAHAYATA – Smart Pilgrim Assistance Platform
> **“Your Journey, Our Responsibility”**

An enterprise-grade, full-stack digital civic assistance platform designed to resolve navigational bottlenecks, emergency medical opacity, high-altitude vulnerabilities, and crowd friction across India's sacred pilgrimage circuits (Shri Mata Vaishno Devi, Shri Mahakaleshwar Ujjain, Char Dham, Omkareshwar, Kashi Vishwanath).

---

## 🎓 Academic Attribution & Developer Profile

- **Principal Developer:** Nidhi Chaudhary
- **Enrollment Number:** 0108CS241082
- **Degree / Major:** Bachelor of Technology (Computer Science & Engineering)
- **Academic Institution:** Samrat Ashok Technological Institute (SATI), Vidisha, Madhya Pradesh, India
- **Live Deployment Host:** [https://nidhi-chaudhary.fwh.is/](https://nidhi-chaudhary.fwh.is/)
- **Academic Year:** 2026

> [!NOTE]
> **Independent Initiative Disclaimer:** YATRI SAHAYATA is an independent digital citizen and pilgrim assistance project developed by student Nidhi Chaudhary. It is **NOT** an official Shrine Board, Temple Trust, or Government authority website. For official biometric permits and VIP slips, yatris must consult authorized administration desks.

---

## ⚡ Instant One-Command Terminal Launch (Zero Setup on macOS)

Python 3 is pre-installed on macOS and requires **zero external dependencies**. Open Terminal and paste:

```bash
cd /Users/tusharchaudhari/.gemini/antigravity/scratch/yatrisahyata && python3 server.py
```

Then open your browser at:
- 🏠 **Main Pilgrim Portal:** [http://localhost:8000/](http://localhost:8000/)
- 🗺️ **Interactive Route Map & POIs:** [http://localhost:8000/routes](http://localhost:8000/routes)
- ✨ **Smart Yatra Planner:** [http://localhost:8000/planner](http://localhost:8000/planner)
- 🏥 **24x7 Emergency Medical Directory:** [http://localhost:8000/medical](http://localhost:8000/medical)
- 🏨 **Verified Dharamshalas & Accommodations:** [http://localhost:8000/accommodation](http://localhost:8000/accommodation)
- 🚨 **Live Request Status Tracker:** [http://localhost:8000/track](http://localhost:8000/track)
- 👤 **Pilgrim User Dashboard:** [http://localhost:8000/dashboard](http://localhost:8000/dashboard)
- 🛡️ **Administrative Control Center:** [http://localhost:8000/admin](http://localhost:8000/admin)

---

## 🔑 Pre-Seeded Test Accounts

| Role | Name | Email Address | Password | Permissions |
|---|---|---|---|---|
| **Platform Administrator** | Nidhi Chaudhary (Admin) | `admin@yatrisahayata.org` | `Admin@123` | Full control over users, requests, medical directory, advisories & audit logs |
| **Pilgrim (User)** | Ramesh Kumar Sharma | `ramesh.sharma@example.com` | `Yatri@123` | Itinerary planner, saved places, request tracking, personal emergency profile |
| **Pilgrim (User)** | Sunita Patel | `sunita.patel@example.com` | `Yatri@123` | Itinerary planner, feedback submissions, saved places |

---

## 🏗️ System Architecture & Technology Stack

The platform is constructed on a **Modular Monolith** architecture with strict separation of concerns:

- **Backend Framework:** Laravel 11.x / PHP 8.3+ with Eloquent ORM, Form Requests, Policies, Middleware, and Database Migrations.
- **Local Zero-Dependency Engine:** Python 3.9+ HTTP/REST server (`server.py`) for instantaneous native local testing.
- **Database Engine:** MySQL 8.0+ (production schema and export file `database/database.sql`) + SQLite 3 (`database/database.sqlite`) for portable deployment.
- **Frontend Layer:** Semantic HTML5, CSS3 Custom Properties (Design System: Navy Blue `#0a192f`, Crisp White `#ffffff`, Saffron Gold `#f59e0b`), Vanilla ES6+ JavaScript, Responsive Grid.
- **Mapping & GIS:** Leaflet.js and OpenStreetMap with color-coded interactive markers for medical aid posts, rest sheds, and routes.
- **Bilingual AI Assistant:** Client-side floating widget communicating via secure backend proxy (`/api/ai/chat`) supporting Hindi and English with deterministic ground-truth fallbacks.

---

## 🌟 Core Modules & Production Features

### 1. Smart Search & Instant Autocomplete
- Database-backed search spanning verified facilities, medical trauma centers, dharamshalas, safety announcements, and FAQs.
- Debounced live JSON autocomplete endpoint (`/api/autocomplete`) with categorized visual badges and empty states.

### 2. Emergency SOS & Consent-Based Location Sharing
- Prominent red emergency SOS button in header with pulsating animation for instant visual recognition.
- Modal captures user's explicit consent to extract precise device GPS coordinates (`navigator.geolocation`) before transmission.
- Generates a unique 16-character tracking code (e.g. `YS-REQ-202611-1001`) with live public tracking timeline.

### 3. Smart Yatra Planner & Itinerary Engine
- Rule-based algorithmic planner taking travel date, duration (1-7 days), group size, budget, and accessibility needs.
- Generates hour-by-hour schedules factoring in battery car operating windows, RFID card collection times, and night wind halts.
- Save to profile, customize, and print/download clean landscape PDF.

### 4. Bilingual AI Yatri Assistant (हिन्दी / English)
- Strict safety guardrails: Emergency queries (chest pain, accident, SOS) instantly present verified hotline numbers (`112` / `108`) and trigger the SOS form.
- Never fabricates fake permits, pricing, or medications; always cites verified Shrine Board guidelines.

### 5. Lost & Found Community Reporting
- Public board displaying lost belongings and found items with filtered categories and moderated privacy protections.

### 6. Administrative Moderation Portal
- High-level KPIs: Active users, total requests, pending emergency alerts, verified medical posts.
- Live request status changer (Pending &rarr; Acknowledged &rarr; In Progress &rarr; Resolved).
- Role management (promoting users to Sevadar/Volunteer or Admin).
- One-click CSV exports for requests, registered users, and feedback reviews.
- Immutable security audit logs recording IP address, user email, model ID, and action.

---

## 📂 Project Directory Structure

```
yatrisahyata/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # User & Admin Authentication
│   │   │   ├── PublicPagesController.php   # Home, About, Guides, Directories
│   │   │   ├── DashboardController.php     # User profile, requests, bookmarks
│   │   │   ├── AdminController.php         # Triage desk, moderation, CSV export
│   │   │   ├── YatraPlannerController.php  # Itinerary generation & saving
│   │   │   ├── AssistanceController.php    # Emergency SOS & request tracker
│   │   │   ├── SearchController.php        # Smart search & autocomplete API
│   │   │   ├── AiAssistantController.php   # Bilingual AI chat service
│   │   │   └── LostFoundController.php     # Lost & found reporting
│   │   └── Middleware/
│   │       ├── Authenticate.php
│   │       └── EnsureAdmin.php             # Strict admin barrier
│   ├── Models/                             # 17 Eloquent Models
│   └── Services/
│       ├── AiService.php                   # Ground-truth AI triage
│       └── YatraPlannerService.php         # Algorithmic scheduler
├── database/
│   ├── migrations/                         # 4 Normalized migration files
│   ├── seeders/DatabaseSeeder.php          # Full realistic demo seed data
│   ├── schema.sql                          # Cross-compatible schema
│   ├── database.sqlite                     # Portable database file
│   └── database.sql                        # Production MySQL 8.0 dump
├── public/
│   ├── css/yatri-main.css                  # Custom Master Design System
│   ├── js/
│   │   ├── yatri-app.js                    # Autocomplete, SOS, Toast, Lang
│   │   ├── yatri-map.js                    # Leaflet.js interactive maps
│   │   └── yatri-ai.js                     # AI Chatbot client widget
│   └── index.php                           # Laravel entrypoint
├── resources/views/
│   ├── layouts/app.blade.php               # Base master layout
│   ├── layouts/admin.blade.php             # Admin layout with sidebar
│   ├── components/                         # Navbar, Footer, SOS, AI, Disclaimer
│   ├── pages/                              # Public pages
│   ├── auth/                               # Login, Register, Forgot Password
│   ├── dashboard/                          # User profile, requests, planner
│   └── admin/                              # Admin overview, users, triage
├── routes/
│   ├── web.php                             # Web routes
│   └── api.php                             # REST API endpoints
├── screenshots/                            # Real captured Chrome screenshots
├── tests/
│   ├── Feature/AuthTest.php                # Laravel Feature tests
│   ├── Feature/AssistanceTest.php          # Laravel Assistance tests
│   └── test_suite.py                       # 8-Part empirical automated test runner
├── .env.example                            # Production environment template
├── composer.json                           # Dependencies definition
├── server.py                               # Zero-dependency local runtime
└── README.md                               # This documentation
```

---

## 🛠️ Local Setup Guide (XAMPP / Laragon / PHP CLI)

### Prerequisites:
- PHP 8.2 or 8.3 with `pdo_mysql`, `mbstring`, `openssl`, `curl` extensions enabled
- MySQL 8.0+ or MariaDB 10.4+
- Composer 2.x

### Steps:
1. **Clone or Copy Project:**
   ```bash
   cd C:/xampp/htdocs/  # or C:/laragon/www/
   git clone <repository-url> yatrisahayata
   cd yatrisahayata
   ```
2. **Install PHP Dependencies:**
   ```bash
   composer install
   ```
3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Database Configuration in `.env`:**
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=yatrisahayata
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. **Run Migrations & Seed Sample Data:**
   ```bash
   php artisan migrate --seed
   ```
   *(Alternatively, create database `yatrisahayata` in phpMyAdmin and import `database/database.sql` directly).*
6. **Launch Development Server:**
   ```bash
   php artisan serve --port=8000
   ```
   Visit `http://localhost:8000` in your web browser.

---

## 🚀 Production Deployment Guide (FreeWebHosting `fwh.is` / cPanel / InfinityFree)

### 1. File Placement & Structure
On shared hosting (such as FreeWebHosting / InfinityFree / cPanel), web root is typically `public_html/` or `htdocs/`.
1. Upload all project contents into a folder (e.g. `../yatrisahayata_app`).
2. Move the contents of `public/` into `public_html/` (or create a symbolic link / configure `.htaccess` rewrite):
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```

### 2. MySQL Database Setup on cPanel / phpMyAdmin
1. Log into your cPanel or FreeWebHosting control panel.
2. Go to **MySQL Databases** &rarr; Create database `yatrisahayata_db`.
3. Create database user and grant all privileges.
4. Open **phpMyAdmin**, select `yatrisahayata_db`, and click **Import**.
5. Choose file `database/database.sql` from this repository and click **Go**. All 17 normalized tables and sample records will import automatically.

### 3. Production Environment File
Update `.env` on production:
```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nidhi-chaudhary.fwh.is

DB_CONNECTION=mysql
DB_HOST=sqlxxx.fwh.is  # Provided by your hosting provider
DB_DATABASE=your_cpanel_db
DB_USERNAME=your_cpanel_user
DB_PASSWORD=your_cpanel_password
```

---

## 🔒 Security & OWASP Defenses

- **SQL Injection Prevention:** 100% of database interactions leverage Eloquent ORM prepared statements with strict parameter binding.
- **Cross-Site Scripting (XSS):** Blade automated HTML entity escaping (`{{ $var }}`) and input sanitization on all text outputs.
- **Cross-Site Request Forgery (CSRF):** Active `@csrf` tokens validated on all POST state-changing requests.
- **Administrative Access Control:** Dedicated `EnsureAdmin` middleware checks authenticated session claims; public registration is hard-locked to user role ID 2.
- **Audit Logging:** Every administrative update, user registration, login, and emergency SOS submission is written to the immutable `audit_logs` table.

---

## 📊 Empirical Verification & Test Report

Running `python3 tests/test_suite.py` validates the entire end-to-end functionality:

```
======================================================================
🧪 Running YATRI SAHAYATA Verification Test Suite
   Developer: Nidhi Chaudhary | 0108CS241082 | SATI Vidisha
======================================================================

---------------------------------------------------------------------------
TEST CASE                                     | STATUS   | DETAILS
---------------------------------------------------------------------------
✅ Database Schema Integrity (17 Tables)       | PASSED   | All 17 tables verified in 3NF.
✅ Role-Based Access Control Structure         | PASSED   | Admin ID 1 and Pilgrim User ID 2 verified separate.
✅ Emergency Assistance Submission & Tracking  | PASSED   | Request logged with tracking code and pending status.
✅ Smart Yatra Itinerary Generation Engine     | PASSED   | Multi-day itinerary generated with 6 verified activities.
✅ Verified Emergency Medical Hotline Directory | PASSED   | 9 posts active with verified landlines/helplines.
✅ AI Yatri Assistant Guardrails & Safety      | PASSED   | Critical queries route immediately to SOS (112/108).
✅ Database-Backed Smart Search & Autocomplete | PASSED   | Autocomplete query returned 3 facilities.
✅ Security Audit Trail & Access Logging       | PASSED   | System logs 2 immutable security events.
---------------------------------------------------------------------------

🎉 ALL 8 SYSTEM VERIFICATION TEST SUITES PASSED EMPIRICALLY!
```

---

## 📸 Screenshots Portfolio

| Screen | Description | File Path |
|---|---|---|
| **Homepage** | Hero, Smart Search, Quick Circuits, Statistics, Leaflet Map, Rest Sheds, Medical Posts, Weather, FAQs | `screenshots/screenshot_01_homepage.png` |
| **Routes & Map** | Interactive Leaflet OpenStreetMap with POI markers and trek waypoints | `screenshots/screenshot_02_routes_map.png` |
| **Smart Planner** | Algorithmic parameter form and generated day-by-day sacred schedule | `screenshots/screenshot_03_smart_planner.png` |
| **Medical Directory** | 24x7 Emergency Trauma Posts, Oxygen Booths, and Direct Helplines | `screenshots/screenshot_04_medical_directory.png` |
| **Pilgrim Dashboard** | Personal itinerary repository, assistance cases, and feedback review | `screenshots/screenshot_05_pilgrim_dashboard.png` |
| **Admin Control Desk**| Live metrics, emergency triage desk, sevadar dispatcher, and audit logs | `screenshots/screenshot_06_admin_dashboard.png` |

---

## 📞 Support & Inquiries

For academic review, evaluation, or ground data contributions:
- **Developer:** Nidhi Chaudhary
- **Institution:** Samrat Ashok Technological Institute (SATI), Vidisha, MP
- **Department:** Computer Science & Engineering
- **Portal Link:** [https://nidhi-chaudhary.fwh.is/](https://nidhi-chaudhary.fwh.is/)
