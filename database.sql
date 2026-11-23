-- =====================================================================
-- YATRI SAHAYATA – Smart Pilgrim Assistance Platform
-- Production MySQL 8.0+ Database Export
-- Developer: Nidhi Chaudhary | Enrollment: 0108CS241082
-- Samrat Ashok Technological Institute (SATI), Vidisha, Madhya Pradesh
-- Live Host Reference: https://nidhi-chaudhary.fwh.is/
-- Generated At: 2026-11-24 01:30:35
-- =====================================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+05:30';

DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `lost_found_reports`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `feedback`;
DROP TABLE IF EXISTS `faqs`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `saved_places`;
DROP TABLE IF EXISTS `itinerary_items`;
DROP TABLE IF EXISTS `itineraries`;
DROP TABLE IF EXISTS `request_updates`;
DROP TABLE IF EXISTS `assistance_requests`;
DROP TABLE IF EXISTS `accommodations`;
DROP TABLE IF EXISTS `medical_centres`;
DROP TABLE IF EXISTS `facilities`;
DROP TABLE IF EXISTS `profiles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;


    CREATE TABLE `roles` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(50) NOT NULL UNIQUE,
      `display_name` varchar(100) NOT NULL,
      `description` text,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `users` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `role_id` bigint unsigned NOT NULL DEFAULT '2',
      `name` varchar(120) NOT NULL,
      `email` varchar(191) NOT NULL UNIQUE,
      `phone` varchar(20) DEFAULT NULL,
      `password` varchar(255) NOT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT '1',
      `email_verified_at` timestamp NULL DEFAULT NULL,
      `preferred_language` varchar(10) NOT NULL DEFAULT 'en',
      `remember_token` varchar(100) DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `users_role_id_foreign` (`role_id`),
      CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `profiles` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned NOT NULL UNIQUE,
      `emergency_contact_name` varchar(120) DEFAULT NULL,
      `emergency_contact_phone` varchar(20) DEFAULT NULL,
      `blood_group` varchar(10) DEFAULT NULL,
      `medical_conditions` text,
      `city` varchar(100) DEFAULT NULL,
      `state` varchar(100) DEFAULT NULL,
      `accessibility_needs` varchar(255) DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `facilities` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(200) NOT NULL,
      `category` varchar(100) NOT NULL,
      `circuit` varchar(100) NOT NULL,
      `location` varchar(255) NOT NULL,
      `latitude` decimal(10,7) NOT NULL,
      `longitude` decimal(10,7) NOT NULL,
      `operating_hours` varchar(100) DEFAULT '24 Hours',
      `contact_number` varchar(50) DEFAULT NULL,
      `fee_type` varchar(50) DEFAULT 'Free',
      `description` text,
      `is_verified` tinyint(1) NOT NULL DEFAULT '1',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `facilities_circuit_idx` (`circuit`),
      KEY `facilities_category_idx` (`category`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `medical_centres` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(200) NOT NULL,
      `centre_type` varchar(100) NOT NULL,
      `circuit` varchar(100) NOT NULL,
      `location` varchar(255) NOT NULL,
      `latitude` decimal(10,7) NOT NULL,
      `longitude` decimal(10,7) NOT NULL,
      `helpline_number` varchar(50) NOT NULL,
      `doctor_in_charge` varchar(120) DEFAULT NULL,
      `available_facilities` text,
      `is_24x7` tinyint(1) NOT NULL DEFAULT '1',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `medical_circuit_idx` (`circuit`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `accommodations` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(200) NOT NULL,
      `type` varchar(100) NOT NULL,
      `circuit` varchar(100) NOT NULL,
      `address` varchar(255) NOT NULL,
      `latitude` decimal(10,7) NOT NULL,
      `longitude` decimal(10,7) NOT NULL,
      `contact_phone` varchar(50) NOT NULL,
      `price_range` varchar(50) DEFAULT 'Nominal Donation',
      `amenities` text,
      `booking_url` varchar(255) DEFAULT NULL,
      `rating` decimal(3,2) DEFAULT '4.50',
      `is_verified` tinyint(1) NOT NULL DEFAULT '1',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `accommodations_circuit_idx` (`circuit`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `assistance_requests` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned DEFAULT NULL,
      `tracking_code` varchar(40) NOT NULL UNIQUE,
      `request_type` varchar(50) NOT NULL,
      `priority` varchar(20) NOT NULL DEFAULT 'medium',
      `pilgrim_name` varchar(120) NOT NULL,
      `contact_number` varchar(30) NOT NULL,
      `alternate_contact` varchar(30) DEFAULT NULL,
      `circuit` varchar(100) NOT NULL,
      `current_location` varchar(255) NOT NULL,
      `latitude` decimal(10,7) DEFAULT NULL,
      `longitude` decimal(10,7) DEFAULT NULL,
      `description` text NOT NULL,
      `status` varchar(30) NOT NULL DEFAULT 'pending',
      `assigned_to` varchar(120) DEFAULT NULL,
      `resolution_notes` text,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `requests_tracking_idx` (`tracking_code`),
      KEY `requests_status_idx` (`status`),
      CONSTRAINT `requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `request_updates` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `assistance_request_id` bigint unsigned NOT NULL,
      `updated_by` varchar(120) NOT NULL,
      `status` varchar(50) NOT NULL,
      `comment` text NOT NULL,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `updates_request_id_foreign` FOREIGN KEY (`assistance_request_id`) REFERENCES `assistance_requests` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `itineraries` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned NOT NULL,
      `title` varchar(200) NOT NULL,
      `destination_circuit` varchar(100) NOT NULL,
      `start_date` date NOT NULL,
      `duration_days` int NOT NULL DEFAULT '3',
      `group_size` int NOT NULL DEFAULT '2',
      `estimated_budget_inr` decimal(10,2) DEFAULT '5000.00',
      `travel_mode` varchar(50) DEFAULT 'train',
      `accessibility_needs` varchar(200) DEFAULT NULL,
      `notes` text,
      `is_public` tinyint(1) NOT NULL DEFAULT '0',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `itineraries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `itinerary_items` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `itinerary_id` bigint unsigned NOT NULL,
      `day_number` int NOT NULL,
      `time_slot` varchar(50) NOT NULL,
      `activity_title` varchar(200) NOT NULL,
      `activity_description` text,
      `location` varchar(200) NOT NULL,
      `estimated_cost` decimal(8,2) DEFAULT '0.00',
      `tips` text,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `items_itinerary_id_foreign` FOREIGN KEY (`itinerary_id`) REFERENCES `itineraries` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `saved_places` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned NOT NULL,
      `place_type` varchar(50) NOT NULL,
      `place_id` bigint unsigned NOT NULL,
      `place_name` varchar(200) NOT NULL,
      `circuit` varchar(100) NOT NULL,
      `notes` text,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `saved_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `announcements` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `circuit` varchar(100) NOT NULL,
      `priority` varchar(20) NOT NULL DEFAULT 'info',
      `content` text NOT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT '1',
      `published_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `faqs` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `category` varchar(100) NOT NULL,
      `question` text NOT NULL,
      `answer` text NOT NULL,
      `hindi_question` text,
      `hindi_answer` text,
      `display_order` int NOT NULL DEFAULT '0',
      `is_active` tinyint(1) NOT NULL DEFAULT '1',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `feedback` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned DEFAULT NULL,
      `pilgrim_name` varchar(120) NOT NULL,
      `email` varchar(191) DEFAULT NULL,
      `circuit` varchar(100) NOT NULL,
      `rating` int NOT NULL DEFAULT '5',
      `service_aspect` varchar(100) NOT NULL DEFAULT 'Overall Experience',
      `comment` text NOT NULL,
      `is_moderated` tinyint(1) NOT NULL DEFAULT '1',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `notifications` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned NOT NULL,
      `title` varchar(200) NOT NULL,
      `message` text NOT NULL,
      `type` varchar(50) NOT NULL DEFAULT 'info',
      `action_url` varchar(255) DEFAULT NULL,
      `is_read` tinyint(1) NOT NULL DEFAULT '0',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `lost_found_reports` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned DEFAULT NULL,
      `report_type` varchar(20) NOT NULL,
      `item_name` varchar(200) NOT NULL,
      `category` varchar(100) NOT NULL,
      `circuit` varchar(100) NOT NULL,
      `incident_location` varchar(255) NOT NULL,
      `incident_date` date NOT NULL,
      `description` text NOT NULL,
      `contact_person` varchar(120) NOT NULL,
      `contact_phone` varchar(30) NOT NULL,
      `status` varchar(30) NOT NULL DEFAULT 'reported',
      `is_approved` tinyint(1) NOT NULL DEFAULT '1',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      CONSTRAINT `lost_found_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `audit_logs` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint unsigned DEFAULT NULL,
      `user_email` varchar(191) DEFAULT NULL,
      `action` varchar(100) NOT NULL,
      `model_type` varchar(100) DEFAULT NULL,
      `model_id` bigint unsigned DEFAULT NULL,
      `ip_address` varchar(50) DEFAULT NULL,
      `user_agent` text,
      `details` text,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
-- Dumping data for table `roles`
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', 'Platform Administrator with full control over requests, directories, and users', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 'user', 'Pilgrim / Yatri', 'Registered pilgrim with access to planner, saved places, and request tracking', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 'volunteer', 'Sevadar / Volunteer', 'Field volunteer assisting yatris with verified ground updates', '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `password`, `is_active`, `email_verified_at`, `preferred_language`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nidhi Chaudhary (Admin)', 'admin@yatrisahayata.org', '+91 98765 43210', 'e86f78a8a3caf0b60d8e74e5942aa6d86dc150cd3c03338aef25b7d2d7e3acc7', 1, '2026-01-01 00:00:00', 'en', NULL, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 2, 'Ramesh Kumar Sharma', 'ramesh.sharma@example.com', '+91 98260 12345', 'dc26b3e5348ac2e84caaffbd3528aa2a6fb7469137144b36bb96092c3d0072bd', 1, '2026-02-15 10:00:00', 'hi', NULL, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 2, 'Sunita Patel', 'sunita.patel@example.com', '+91 94250 98765', 'dc26b3e5348ac2e84caaffbd3528aa2a6fb7469137144b36bb96092c3d0072bd', 1, '2026-03-10 11:30:00', 'en', NULL, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(4, 3, 'Amit Verma (Sevadar)', 'amit.sevadar@yatrisahayata.org', '+91 91234 56789', 'dc26b3e5348ac2e84caaffbd3528aa2a6fb7469137144b36bb96092c3d0072bd', 1, '2026-02-01 09:00:00', 'en', NULL, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `profiles`
INSERT INTO `profiles` (`id`, `user_id`, `emergency_contact_name`, `emergency_contact_phone`, `blood_group`, `medical_conditions`, `city`, `state`, `accessibility_needs`, `created_at`, `updated_at`) VALUES
(1, 1, 'SATI Helpline Desk', '+91 75922 50121', 'O+', 'None', 'Vidisha', 'Madhya Pradesh', 'None', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 2, 'Anil Sharma (Brother)', '+91 98260 54321', 'B+', 'Mild Hypertension (carries medication)', 'Indore', 'Madhya Pradesh', 'Needs frequent hydration stops', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 3, 'Vikram Patel (Spouse)', '+91 94250 12345', 'A+', 'None', 'Ahmedabad', 'Gujarat', 'Wheelchair assistance requested for senior elder', '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `facilities`
INSERT INTO `facilities` (`id`, `name`, `category`, `circuit`, `location`, `latitude`, `longitude`, `operating_hours`, `contact_number`, `fee_type`, `description`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'Katra Yatri Registration Counter & RFID Center', 'Registration Counter', 'Vaishno Devi', 'Railway Station & Bus Stand, Katra, J&K', 32.9928, 74.9318, '24 Hours', '01991-232125', 'Free', 'Official RFID Yatra Parchi issuance center. Mandatory for all pilgrims before starting climb.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 'Banganga Checkpost & Luggage Cloakroom', 'Cloak Room', 'Vaishno Devi', 'Banganga Starting Point, Katra', 33.0035, 74.945, '24 Hours', '01991-232029', 'Free / Nominal', 'Secure cloakroom, security screening, and free drinking water refill station.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 'Ardhkuwari Resting Hall & Blanket Counter', 'Resting Camp', 'Vaishno Devi', 'Track KM 6, Ardhkuwari Complex', 33.0189, 74.9542, '24 Hours', '01991-232888', 'Refundable Security', 'Covered pilgrim shed, free drinking water, hot milk counter, clean restrooms, and sanitized blankets.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(4, 'Sanjichhat Helipad & Battery Car Stand', 'Battery Car', 'Vaishno Devi', 'Sanjichhat Ridge (Elevation 1780m)', 33.0285, 74.952, '06:00 AM - 05:00 PM', '01991-232444', 'Standard Fare', 'Battery car boarding for senior citizens and disabled yatris towards Bhawan (approx 2.5 km).', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(5, 'Mata Vaishno Devi Bhawan Free Cloakroom', 'Cloak Room', 'Vaishno Devi', 'Near Gate No. 2, Bhawan Complex', 33.0308, 74.949, '24 Hours', '01991-232010', 'Free', 'Safe locker storage for mobile phones, leather belts, and backpacks before cave entrance.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(6, 'Shri Mahakaleshwar Pilgrim Facilitation Center', 'Resting Camp', 'Ujjain Mahakal', 'Gate No. 4, Mahakal Lok, Ujjain, MP', 23.1827, 75.7682, '04:00 AM - 11:00 PM', '0734-2550563', 'Free', 'Air-conditioned waiting lounges, drinking water, prasad counters, and special queue management.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(7, 'Ujjain Free Locker & Shoe Counter', 'Cloak Room', 'Ujjain Mahakal', 'Near Mahakal Mandir Entry Gate 1', 23.1835, 75.7675, '24 Hours', '0734-2550564', 'Free', 'Numbered token shoe stands and electronic safe lockers for valuables.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(8, 'Omkareshwar Narmada Boat Ghat & Safety Hub', 'Boat Ghat', 'Omkareshwar', 'Nagar Ghat, Omkareshwar Island, MP', 22.2464, 76.1511, '06:00 AM - 07:00 PM', '07280-271224', 'Nominal', 'Govt certified life jackets mandatory. Direct boat transfer between Omkareshwar and Mamleshwar.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(9, 'Kedarnath GMVN Base Camp Rest Shelter', 'Resting Camp', 'Char Dham', 'Kedarnath Valley, Rudraprayag, Uttarakhand', 30.7352, 79.0669, '24 Hours', '01364-267228', 'Nominal', 'Insulated pre-fab tents, sleeping bags, hot tea/meals, and oxygen assistance for pilgrims.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(10, 'Badrinath Yatri Sahayata & Wheelchair Center', 'Wheelchair Support', 'Char Dham', 'Near Main Temple Bridge, Badrinath', 30.7447, 79.4938, '05:00 AM - 09:00 PM', '01381-222212', 'Free with ID', 'Assisted wheelchair movement for elderly pilgrims, battery shuttle, and medical accompaniment.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(11, 'Kashi Vishwanath Corridor Facilitation Lounge', 'Resting Camp', 'Varanasi Kashi', 'Godowlia Gate No. 4, Varanasi, UP', 25.3109, 83.0107, '24 Hours', '0542-2392629', 'Free', 'Spacious pilgrim waiting hall, RO water, wheelchair ramps, and electronic darshan counters.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `medical_centres`
INSERT INTO `medical_centres` (`id`, `name`, `centre_type`, `circuit`, `location`, `latitude`, `longitude`, `helpline_number`, `doctor_in_charge`, `available_facilities`, `is_24x7`, `created_at`, `updated_at`) VALUES
(1, 'Shri Mata Vaishno Devi Emergency Hospital', 'Emergency Trauma Hospital', 'Vaishno Devi', 'Banganga Road, Katra Base, J&K', 33.0012, 74.9421, '01991-232044', 'Dr. Rajesh Sharma, MD', 'ICU, Cardiac Life Support, 24x7 Ambulance, Digital X-Ray, Blood Bank', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 'Ardhkuwari Midpoint Dispensary & Oxygen Booth', 'Dispensary', 'Vaishno Devi', 'Ardhkuwari Track KM 6', 33.0185, 74.954, '01991-232190', 'Dr. Meenakshi Jamwal, MBBS', 'Oxygen Concentrators, Nebulizers, ECG, Blister care, Pain relief injections', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 'Sanjichhat Emergency Medical Unit', 'First Aid & Critical Care', 'Vaishno Devi', 'Near Sanjichhat Helipad', 33.028, 74.9515, '01991-232195', 'Dr. Sunil Koul, Critical Care', 'High-altitude hypoxia stabilization, defibrillators, emergency stretchers', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(4, 'Bhawan Medical Aid Centre (Gate 3)', 'Hospital / Critical Care', 'Vaishno Devi', 'Mata Vaishno Devi Bhawan Complex', 33.0312, 74.9485, '01991-232115', 'Dr. Virender Tickoo, CMO', 'Full critical care post, emergency surgery prep, cardiac monitoring, oxygen bank', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(5, 'Mahakaleshwar Emergency First Aid Unit', 'Emergency Hospital', 'Ujjain Mahakal', 'Mahakal Mandir Premises, Gate 2, Ujjain', 23.183, 75.768, '0734-2551444', 'Dr. Arvind Saxena, MBBS', 'Paramedic team on site, cardiac defibrillators, heat exhaustion triage, 2 ambulances', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(6, 'District Civil Hospital Ujjain (Pilgrim Ward)', 'Super Specialty Hospital', 'Ujjain Mahakal', 'Agar Road, Ujjain, MP', 23.1885, 75.7792, '0734-2551000', 'Medical Superintendent', 'Trauma ward, orthopedic emergency, dedicated pilgrim care helpline, 108 ambulance hub', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(7, 'Kedarnath High Altitude Medical Post & Hyperbaric Pod', 'High Altitude Emergency', 'Char Dham', 'Kedarnath Temple Compound', 30.735, 79.0665, '01364-267250', 'Dr. H.S. Rawat, High-Altitude Medicine', 'Hyperbaric chamber for severe mountain sickness, oxygen therapy, hypothermia warmers', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(8, 'Badrinath Primary Health Centre & Cardiac Care', 'Dispensary', 'Char Dham', 'Main Bazaar, Badrinath', 30.744, 79.493, '01381-222234', 'Dr. Preeti Bisht, MBBS', 'Cardiac emergency care, oxygen concentrators, general medicine dispensary, ambulance', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(9, 'Kashi Vishwanath Corridor First Aid Post', 'First Aid Booth', 'Varanasi Kashi', 'Corridor Gate No. 2, Varanasi', 25.3115, 83.0112, '0542-2392630', 'Dr. Anand Mishra, MBBS', 'Heat stroke triage, dehydration IV fluids, first aid, emergency stretcher service', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `accommodations`
INSERT INTO `accommodations` (`id`, `name`, `type`, `circuit`, `address`, `latitude`, `longitude`, `contact_phone`, `price_range`, `amenities`, `booking_url`, `rating`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'Niharika Yatri Niwas (Shrine Board)', 'Trust Guest House', 'Vaishno Devi', 'Railway Road, Katra, Jammu & Kashmir', 32.9935, 74.9325, '01991-232029', '₹400 - ₹1200 / Room', 'Air-cooled rooms, clean attached washrooms, vegetarian Bhojanalaya, lift, 24x7 hot water', 'https://www.maavaishnodevi.org/', 4.7, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 'Vaishnavi Dham Pilgrim Guest House', 'Dharamshala', 'Vaishno Devi', 'Near Jammu Tawi Railway Station, Jammu', 32.7058, 74.8722, '0191-2473270', '₹150 - ₹600 / Bed', 'Dormitory beds, family rooms, luggage cloakroom, yatra slip assistance desk', 'https://www.maavaishnodevi.org/', 4.5, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 'Ardhkuwari Dormitory & Blanket Shelter', 'Dharamshala', 'Vaishno Devi', 'Ardhkuwari Station, Track Midpoint', 33.0192, 74.9545, '01991-232888', '₹120 / Bed (Nominal)', 'Warm clean sleeping hall, safe lockers, 24hr tea and hot water, doctor nearby', 'https://www.maavaishnodevi.org/', 4.4, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(4, 'Shree Mahakal Bhakt Niwas', 'Dharamshala', 'Ujjain Mahakal', 'Near Harifatak Overbridge, Ujjain, MP', 23.175, 75.772, '0734-2550563', '₹300 - ₹800 / Room', 'AC & Non-AC family rooms, pure sattvic canteen, RO water, spacious parking, lift', 'https://shrimahakaleshwar.com/', 4.6, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(5, 'Avantika Yatri Niwas', 'Trust Guest House', 'Ujjain Mahakal', 'Jaisinghpura, Near Mahakal Mandir, Ujjain', 23.181, 75.765, '0734-2551222', '₹500 - ₹1100 / Room', 'Solar water heating, 24hr security, temple shuttle, clean bedding, free WiFi in lobby', 'https://shrimahakaleshwar.com/', 4.3, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(6, 'GMVN Tourist Bungalow & Huts Kedarnath', 'Govt Guest House', 'Char Dham', 'Kedarnath Base Area, Uttarakhand', 30.7345, 79.0655, '01364-267228', '₹600 - ₹2500 / Room', 'Weatherproof insulated cottages, hot water buckets, oxygen cylinders on call, canteen', 'https://gmvnonline.com/', 4.5, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(7, 'Kali Kamli Dharamshala Badrinath', 'Dharamshala', 'Char Dham', 'Near Temple Gate, Badrinath Dham', 30.745, 79.4935, '01381-222210', 'Nominal Donation (₹200 - ₹500)', 'Traditional pilgrimage dharamshala, clean quilts, hot water morning hours, langar service', 'https://badrinath-kedarnath.gov.in/', 4.4, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(8, 'Shree Gajanan Maharaj Sansthan Bhakt Niwas', 'Trust Guest House', 'Omkareshwar', 'Mamleshwar Temple Road, Omkareshwar, MP', 22.244, 76.148, '07280-271240', 'Nominal Donation', 'Ultra-clean rooms, serene atmosphere, morning Mahaprasad, elderly assistance ramps', 'https://gajananmaharaj.org/', 4.8, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(9, 'Kashi Vishwanath Annakshetra & Yatri Niwas', 'Trust Guest House', 'Varanasi Kashi', 'Mishir Pokhra, Godowlia, Varanasi', 25.309, 83.008, '0542-2392635', 'Free / Voluntary Donation', 'Free hot meals (Mahaprasad) for all pilgrims, clean bedding, luggage lockers', 'https://shrikashivishwanath.org/', 4.7, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `assistance_requests`
INSERT INTO `assistance_requests` (`id`, `user_id`, `tracking_code`, `request_type`, `priority`, `pilgrim_name`, `contact_number`, `alternate_contact`, `circuit`, `current_location`, `latitude`, `longitude`, `description`, `status`, `assigned_to`, `resolution_notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'YS-REQ-202611-1001', 'wheelchair', 'medium', 'Ramesh Kumar Sharma', '+91 98260 12345', '+91 98260 54321', 'Vaishno Devi', 'Sanjichhat Ridge near Battery Car Booth', 33.0285, 74.952, 'Requesting assistance wheelchair for my elderly mother (68 yrs) from Sanjichhat to Bhawan Gate 2.', 'in_progress', 'Amit Verma (Sevadar)', 'Volunteer Amit dispatched with standard foldable wheelchair from Sanjichhat post.', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, NULL, 'YS-REQ-202611-1002', 'medical', 'critical', 'Govind Prasad', '+91 94250 88776', '+91 94250 88777', 'Vaishno Devi', 'Track KM 4 near Himkoti View Point', 33.012, 74.949, 'Yatri experiencing mild dizziness, high pulse, and dehydration. Resting on bench.', 'resolved', 'Dr. Meenakshi Jamwal', 'Attended by Himkoti first aid paramedic. ORS provided, vitals stabilized. Advised 45 min rest before proceeding.', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 3, 'YS-REQ-202611-1003', 'lost_person', 'high', 'Sunita Patel', '+91 94250 98765', '+91 94250 12345', 'Ujjain Mahakal', 'Mahakal Lok Fountain Square', 23.1825, 75.768, 'Elderly uncle (wearing white kurta, blue cap) separated from family group during evening Aarti rush.', 'resolved', 'Mahakal Seva Desk', 'Announced over PA system. Reunited with family at Gate 4 Facilitation Center within 25 minutes.', '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `request_updates`
INSERT INTO `request_updates` (`id`, `assistance_request_id`, `updated_by`, `status`, `comment`, `created_at`) VALUES
(1, 1, 'System', 'pending', 'Assistance request logged by pilgrim Ramesh Kumar Sharma.', '2026-11-23 20:00:35'),
(2, 1, 'Admin (Nidhi Chaudhary)', 'acknowledged', 'Request verified and assigned to Katra Sevadar team.', '2026-11-23 20:00:35'),
(3, 1, 'Amit Verma (Sevadar)', 'in_progress', 'Sevadar en route with wheelchair to Sanjichhat Ridge.', '2026-11-23 20:00:35'),
(4, 2, 'System', 'pending', 'Emergency medical alert triggered via SOS portal.', '2026-11-23 20:00:35'),
(5, 2, 'Dr. Meenakshi Jamwal', 'in_progress', 'Paramedic dispatched with first-aid kit and portable oxygen kit.', '2026-11-23 20:00:35'),
(6, 2, 'Dr. Meenakshi Jamwal', 'resolved', 'Pilgrim examined, vitals normal after rehydration. Case marked resolved.', '2026-11-23 20:00:35');

-- Dumping data for table `itineraries`
INSERT INTO `itineraries` (`id`, `user_id`, `title`, `destination_circuit`, `start_date`, `duration_days`, `group_size`, `estimated_budget_inr`, `travel_mode`, `accessibility_needs`, `notes`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 2, 'Spiritual Vaishno Devi Yatra with Elderly Parents', 'Vaishno Devi', '2026-12-05', 3, 3, 14500, 'train', 'Wheelchair assistance required for track; frequent hydration breaks', 'Plan includes battery car from Sanjichhat and ropeway for Bhairon Ghati.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `itinerary_items`
INSERT INTO `itinerary_items` (`id`, `itinerary_id`, `day_number`, `time_slot`, `activity_title`, `activity_description`, `location`, `estimated_cost`, `tips`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '08:00 AM - 11:00 AM', 'Arrival in Katra & Check-in', 'Arrive at Katra Railway Station, obtain RFID Yatra slips at station counter, check in to Niharika Yatri Niwas.', 'Katra Base Camp', 1200, 'Carry original Aadhaar cards in physical copy for quick RFID issuance.', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 1, 1, '02:00 PM - 06:00 PM', 'Banganga to Ardhkuwari Ascent', 'Begin trek via the new Tarakote Marg (smoother gradient, gentler climb suitable for elders).', 'Banganga - Ardhkuwari Track', 300, 'Keep walking poles and refill drinking water at free RO filter posts.', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 1, 1, '07:30 PM - 10:00 PM', 'Night Halt & Rest at Ardhkuwari', 'Halt at Ardhkuwari resting shelter, have hot herbal tea, rest overnight.', 'Ardhkuwari Complex', 600, 'Collect sanitized blankets against refundable deposit.', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(4, 1, 2, '06:00 AM - 09:30 AM', 'Battery Car to Bhawan & Holy Darshan', 'Board morning battery car at Sanjichhat to Bhawan, deposit phones in free cloakroom, proceed for holy cave Darshan.', 'Mata Vaishno Devi Bhawan', 700, 'Senior citizens have dedicated queue assistance at Gate No. 2.', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(5, 1, 2, '11:00 AM - 01:00 PM', 'Passenger Ropeway to Bhairon Ghati', 'Take the modern cable ropeway car to Bhairon Temple (saving 2 hours of steep trekking).', 'Bhairon Temple', 300, 'Ticket valid for 2 hours; carry light snacks.', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(6, 1, 2, '03:00 PM - 08:00 PM', 'Gentle Descent & Return to Katra', 'Descend back towards Katra base via Himkoti route, rest at hotel, enjoy evening Prasad.', 'Katra Base Hotel', 500, 'Sturdy walking shoes prevent foot fatigue.', '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `announcements`
INSERT INTO `announcements` (`id`, `title`, `circuit`, `priority`, `content`, `is_active`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Pre-Monsoon Weather Advisory & Rain Gear Recommendation', 'Vaishno Devi', 'advisory', 'Light to moderate intermittent showers predicted in the Trikuta hills over the next 48 hours. Yatris are strongly advised to carry waterproof jackets, raincoat ponchos, and non-skid walking shoes. Track track cleaners are deployed continuously.', 1, '2026-11-20 08:00:00', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 'Bhasma Aarti Registration Gate & Pre-Verification Notice', 'Ujjain Mahakal', 'alert', 'Due to high weekend turnout, pilgrims with online Bhasma Aarti tokens must report at Gate No. 3 at least 90 minutes prior to entry (by 02:30 AM) with original Aadhaar cards. Mobile phones strictly prohibited inside the sanctum sanctorum.', 1, '2026-11-22 10:30:00', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 'High-Altitude Acclimatization Notice for Kedarnath & Amarnath', 'Char Dham', 'advisory', 'All pilgrims traveling above 3,000 meters are urged to halt overnight at lower elevation transit camps (Sonprayag / Guptkashi) to prevent Acute Mountain Sickness (AMS). Free health checkups and pulse oximeter testing are available at all government medical tents.', 1, '2026-11-23 09:15:00', '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(4, 'Battery Car & Ropeway Timings Update', 'Vaishno Devi', 'info', 'The Battery Car service between Sanjichhat and Bhawan is operational from 07:00 AM to 05:00 PM for senior citizens and differently-abled yatris. Bhairon Ghati Passenger Ropeway runs smoothly between 08:00 AM and 04:30 PM (subject to high wind conditions).', 1, '2026-11-23 14:00:00', '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `faqs`
INSERT INTO `faqs` (`id`, `category`, `question`, `answer`, `hindi_question`, `hindi_answer`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Registration & Yatra Slip', 'Is online registration or RFID Yatra slip mandatory before visiting?', 'Yes. For shrines like Mata Vaishno Devi, Char Dham, and Amarnath, every pilgrim (regardless of age) must carry an official RFID card or biometric registration slip. These are issued free of cost at railway station and bus stand counters upon presenting valid government photo ID.', 'क्या यात्रा शुरू करने से पहले ऑनलाइन पंजीकरण या RFID पर्ची अनिवार्य है?', 'हाँ। माता वैष्णो देवी, चार धाम और अमरनाथ जैसी यात्राओं के लिए प्रत्येक तीर्थयात्री के पास वैध RFID कार्ड या बायोमेट्रिक पंजीकरण पर्ची होना अनिवार्य है। यह रेलवे स्टेशन और बस स्टैंड काउंटरों पर वैध फोटो पहचान पत्र दिखाकर निःशुल्क प्राप्त किया जा सकता है।', 1, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 'Medical & Fitness', 'What medical support is available for senior citizens or cardiac patients on the track?', 'Designated medical units and first aid dispensaries with oxygen concentrators, ECG, and emergency paramedics are stationed every 2-3 km along the main routes. In case of breathless or chest discomfort, immediately contact the nearest post or activate the emergency SOS button on this portal.', 'ट्रैक पर वरिष्ठ नागरिकों या हृदय रोगियों के लिए क्या चिकित्सा सहायता उपलब्ध है?', 'प्रमुख यात्रा मार्गों पर हर 2-3 किमी की दूरी पर ऑक्सीजन कंसंट्रेटर, ईसीजी और आपातकालीन पैरामेडिक्स से लैस चिकित्सा इकाइयां और प्राथमिक उपचार केंद्र उपलब्ध हैं। सांस लेने में कठिनाई या किसी भी अस्वस्थता की स्थिति में तुरंत निकटतम केंद्र से संपर्क करें या इस पोर्टल पर SOS बटन दबाएं।', 2, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, 'Luggage & Cloak Rooms', 'Where can I safely store my luggage, mobile phone, and leather items?', 'All major shrines offer computerized free or nominal-charge cloakrooms and safe lockers near base camps and main temple entrances. Leather belts, purses, electronic items, and shoe storage counters are clearly marked with computerized receipts.', 'मैं अपना सामान, मोबाइल फोन और चमड़े की वस्तुएं सुरक्षित कहां रख सकता हूं?', 'सभी प्रमुख धार्मिक स्थलों पर बेस कैंप और मुख्य मंदिर के प्रवेश द्वारों के पास कम्प्यूटरीकृत निःशुल्क या मामूली शुल्क वाले क्लॉक रूम और लॉकर उपलब्ध हैं। चमड़े के बेल्ट, पर्स और इलेक्ट्रॉनिक सामान के लिए टोकन काउंटर स्थापित हैं।', 3, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(4, 'Transport & Battery Car', 'How can elderly pilgrims or persons with disabilities book battery cars or palanquins (Palki)?', 'Authorized battery cars (running on smooth track sections) can be booked at designated booking counters or online through official shrine portals for seniors (60+ years) and medically certified individuals. Authorized pony, pithu, and palki rates are strictly fixed by district administration.', 'बुजुर्ग तीर्थयात्री या दिव्यांगजन बैटरी कार या पालकी (पालकी) कैसे बुक कर सकते हैं?', 'वरिष्ठ नागरिकों और चिकित्सकीय रूप से प्रमाणित व्यक्तियों के लिए निर्धारित काउंटरों पर या आधिकारिक पोर्टल के माध्यम से बैटरी कार बुक की जा सकती है। अधिकृत टट्टू, पिठू और पालकी की दरें जिला प्रशासन द्वारा पूर्व-निर्धारित होती हैं।', 4, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(5, 'Accommodations & Rest', 'How can pilgrims reserve clean and economical accommodation in dharamshalas?', 'Pilgrims are encouraged to book shrine board guest houses or registered trust dharamshalas in advance. Offline walk-in dormitories and blanket counters are also maintained 24x7 for spontaneous travelers on a first-come, first-served basis.', 'तीर्थयात्री धर्मशालाओं में स्वच्छ और किफायती आवास कैसे बुक कर सकते हैं?', 'तीर्थयात्रियों को सलाह दी जाती है कि वे श्राइन बोर्ड के गेस्ट हाउस या पंजीकृत ट्रस्ट धर्मशालाओं में पहले से बुकिंग करें। आकस्मिक आने वाले यात्रियों के लिए 24x7 निःशुल्क/रियायती डॉर्मिटरी और कंबल काउंटर भी पहले आओ-पहले पाओ के आधार पर संचालित होते हैं।', 5, 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `feedback`
INSERT INTO `feedback` (`id`, `user_id`, `pilgrim_name`, `email`, `circuit`, `rating`, `service_aspect`, `comment`, `is_moderated`, `created_at`, `updated_at`) VALUES
(1, 2, 'Ramesh Kumar Sharma', 'ramesh.sharma@example.com', 'Vaishno Devi', 5, 'Route & Medical Guide', 'The route altitude guidance and emergency oxygen centre locations helped my 68-year-old mother complete darshan comfortably. Truly a blessing!', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, 3, 'Sunita Patel', 'sunita.patel@example.com', 'Ujjain Mahakal', 5, 'Smart Yatra Planner', 'The 2-day customized itinerary generated by YATRI SAHAYATA saved us hours of standing in unorganized lines. Highly intuitive platform.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(3, NULL, 'Dr. B. K. Agrawal', 'bkagrawal@satiengg.in', 'Char Dham', 5, 'Overall Experience', 'Proud to see student Nidhi Chaudhary develop such an impactful, real-world community assistance platform for pilgrims across India.', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `notifications`
INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `action_url`, `is_read`, `created_at`) VALUES
(1, 2, 'Assistance Request Assigned', 'Your wheelchair request (YS-REQ-202611-1001) has been assigned to volunteer Amit Verma.', 'success', '/dashboard/requests', 0, '2026-11-23 20:00:35'),
(2, 2, 'Weather Advisory for Vaishno Devi', 'Pre-monsoon showers expected. Remember to carry rain covers.', 'warning', '/weather', 0, '2026-11-23 20:00:35'),
(3, 3, 'Itinerary Ready', 'Your Smart Yatra Plan for Ujjain Mahakal has been generated and saved to your profile.', 'info', '/dashboard/planner', 0, '2026-11-23 20:00:35');

-- Dumping data for table `lost_found_reports`
INSERT INTO `lost_found_reports` (`id`, `user_id`, `report_type`, `item_name`, `category`, `circuit`, `incident_location`, `incident_date`, `description`, `contact_person`, `contact_phone`, `status`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 2, 'lost', 'Brown Leather Travel Pouch with Train Tickets', 'Bag/Luggage', 'Vaishno Devi', 'Near Banganga Cloak Room Counter', '2026-11-21', 'Lost a small brown zip pouch containing return train tickets and prescription spectacles.', 'Ramesh Sharma', '+91 98260 12345', 'reported', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35'),
(2, NULL, 'found', 'Titan Wristwatch (Silver Dial, Steel Strap)', 'Jewellery', 'Ujjain Mahakal', 'Near Mahakal Lok Entrance Bench', '2026-11-22', 'Found an elegant silver steel wristwatch on marble bench. Deposited with Police Seva Chowki Gate 4.', 'Inspector Vijay Rao', '+91 734-2550100', 'verified', 1, '2026-11-23 20:00:35', '2026-11-23 20:00:35');

-- Dumping data for table `audit_logs`
INSERT INTO `audit_logs` (`id`, `user_id`, `user_email`, `action`, `model_type`, `model_id`, `ip_address`, `user_agent`, `details`, `created_at`) VALUES
(1, 1, 'admin@yatrisahayata.org', 'SYSTEM_INITIALIZATION', 'System', 1, '127.0.0.1', 'YatriSahayata/Installer', 'Platform initialized with standard pilgrim assistance schema and seed data by Nidhi Chaudhary.', '2026-11-23 20:00:35'),
(2, 2, 'ramesh.sharma@example.com', 'USER_LOGIN', 'User', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh)', 'User logged in successfully.', '2026-11-23 20:00:35');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;