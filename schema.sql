-- =====================================================================
-- YATRI SAHAYATA - Smart Pilgrim Assistance Platform
-- Database Schema for MySQL 8 & SQLite compatibility
-- Developer: Nidhi Chaudhary (0108CS241082)
-- Samrat Ashok Technological Institute, Vidisha, MP
-- =====================================================================

CREATE TABLE IF NOT EXISTS roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    role_id INTEGER NOT NULL DEFAULT 2,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    is_active INTEGER DEFAULT 1,
    email_verified_at DATETIME NULL,
    preferred_language VARCHAR(10) DEFAULT 'en',
    remember_token VARCHAR(100) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS profiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL UNIQUE,
    emergency_contact_name VARCHAR(120) NULL,
    emergency_contact_phone VARCHAR(20) NULL,
    blood_group VARCHAR(10) NULL,
    medical_conditions TEXT NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    accessibility_needs VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS facilities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL,
    circuit VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    operating_hours VARCHAR(100) DEFAULT '24 Hours',
    contact_number VARCHAR(50) NULL,
    fee_type VARCHAR(50) DEFAULT 'Free',
    description TEXT,
    is_verified INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS medical_centres (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(200) NOT NULL,
    centre_type VARCHAR(100) NOT NULL,
    circuit VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    helpline_number VARCHAR(50) NOT NULL,
    doctor_in_charge VARCHAR(120) NULL,
    available_facilities TEXT,
    is_24x7 INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS accommodations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(200) NOT NULL,
    type VARCHAR(100) NOT NULL,
    circuit VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    contact_phone VARCHAR(50) NOT NULL,
    price_range VARCHAR(50) DEFAULT 'Nominal Donation',
    amenities TEXT,
    booking_url VARCHAR(255) NULL,
    rating DECIMAL(3, 2) DEFAULT 4.5,
    is_verified INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS assistance_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    tracking_code VARCHAR(40) NOT NULL UNIQUE,
    request_type VARCHAR(50) NOT NULL,
    priority VARCHAR(20) DEFAULT 'medium',
    pilgrim_name VARCHAR(120) NOT NULL,
    contact_number VARCHAR(30) NOT NULL,
    alternate_contact VARCHAR(30) NULL,
    circuit VARCHAR(100) NOT NULL,
    current_location VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 7) NULL,
    longitude DECIMAL(10, 7) NULL,
    description TEXT NOT NULL,
    status VARCHAR(30) DEFAULT 'pending',
    assigned_to VARCHAR(120) NULL,
    resolution_notes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS request_updates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    assistance_request_id INTEGER NOT NULL,
    updated_by VARCHAR(120) NOT NULL,
    status VARCHAR(50) NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assistance_request_id) REFERENCES assistance_requests(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS itineraries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title VARCHAR(200) NOT NULL,
    destination_circuit VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    duration_days INTEGER DEFAULT 3,
    group_size INTEGER DEFAULT 2,
    estimated_budget_inr DECIMAL(10, 2) DEFAULT 5000.00,
    travel_mode VARCHAR(50) DEFAULT 'train',
    accessibility_needs VARCHAR(200) NULL,
    notes TEXT NULL,
    is_public INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS itinerary_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    itinerary_id INTEGER NOT NULL,
    day_number INTEGER NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    activity_title VARCHAR(200) NOT NULL,
    activity_description TEXT,
    location VARCHAR(200) NOT NULL,
    estimated_cost DECIMAL(8, 2) DEFAULT 0.00,
    tips TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (itinerary_id) REFERENCES itineraries(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS saved_places (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    place_type VARCHAR(50) NOT NULL,
    place_id INTEGER NOT NULL,
    place_name VARCHAR(200) NOT NULL,
    circuit VARCHAR(100) NOT NULL,
    notes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS announcements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    circuit VARCHAR(100) NOT NULL,
    priority VARCHAR(20) DEFAULT 'info',
    content TEXT NOT NULL,
    is_active INTEGER DEFAULT 1,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS faqs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category VARCHAR(100) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    hindi_question TEXT NULL,
    hindi_answer TEXT NULL,
    display_order INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS feedback (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    pilgrim_name VARCHAR(120) NOT NULL,
    email VARCHAR(191) NULL,
    circuit VARCHAR(100) NOT NULL,
    rating INTEGER DEFAULT 5,
    service_aspect VARCHAR(100) DEFAULT 'Overall Experience',
    comment TEXT NOT NULL,
    is_moderated INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    action_url VARCHAR(255) NULL,
    is_read INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lost_found_reports (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    report_type VARCHAR(20) NOT NULL,
    item_name VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL,
    circuit VARCHAR(100) NOT NULL,
    incident_location VARCHAR(255) NOT NULL,
    incident_date DATE NOT NULL,
    description TEXT NOT NULL,
    contact_person VARCHAR(120) NOT NULL,
    contact_phone VARCHAR(30) NOT NULL,
    status VARCHAR(30) DEFAULT 'reported',
    is_approved INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    user_email VARCHAR(191) NULL,
    action VARCHAR(100) NOT NULL,
    model_type VARCHAR(100) NULL,
    model_id INTEGER NULL,
    ip_address VARCHAR(50) NULL,
    user_agent TEXT NULL,
    details TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_requests_tracking ON assistance_requests(tracking_code);
CREATE INDEX IF NOT EXISTS idx_requests_status ON assistance_requests(status);
CREATE INDEX IF NOT EXISTS idx_facilities_circuit ON facilities(circuit);
CREATE INDEX IF NOT EXISTS idx_medical_circuit ON medical_centres(circuit);
CREATE INDEX IF NOT EXISTS idx_acc_circuit ON accommodations(circuit);
CREATE INDEX IF NOT EXISTS idx_itineraries_user ON itineraries(user_id);
