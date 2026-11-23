<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full administrative access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'user', 'display_name' => 'Pilgrim / Yatri', 'description' => 'Pilgrim with planner and request access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'volunteer', 'display_name' => 'Sevadar / Volunteer', 'description' => 'Field volunteer assisting yatris', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Users (Admin + Demo Pilgrims)
        DB::table('users')->insertOrIgnore([
            [
                'id' => 1,
                'role_id' => 1,
                'name' => 'Nidhi Chaudhary (Admin)',
                'email' => 'admin@yatrisahayata.org',
                'phone' => '+91 98765 43210',
                'password' => Hash::make('Admin@123'),
                'is_active' => 1,
                'email_verified_at' => now(),
                'preferred_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'role_id' => 2,
                'name' => 'Ramesh Kumar Sharma',
                'email' => 'ramesh.sharma@example.com',
                'phone' => '+91 98260 12345',
                'password' => Hash::make('Yatri@123'),
                'is_active' => 1,
                'email_verified_at' => now(),
                'preferred_language' => 'hi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'role_id' => 2,
                'name' => 'Sunita Patel',
                'email' => 'sunita.patel@example.com',
                'phone' => '+91 94250 98765',
                'password' => Hash::make('Yatri@123'),
                'is_active' => 1,
                'email_verified_at' => now(),
                'preferred_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Profiles
        DB::table('profiles')->insertOrIgnore([
            [
                'user_id' => 1,
                'emergency_contact_name' => 'SATI Helpline Desk',
                'emergency_contact_phone' => '+91 75922 50121',
                'blood_group' => 'O+',
                'medical_conditions' => 'None',
                'city' => 'Vidisha',
                'state' => 'Madhya Pradesh',
                'accessibility_needs' => 'None',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'emergency_contact_name' => 'Anil Sharma (Brother)',
                'emergency_contact_phone' => '+91 98260 54321',
                'blood_group' => 'B+',
                'medical_conditions' => 'Mild Hypertension',
                'city' => 'Indore',
                'state' => 'Madhya Pradesh',
                'accessibility_needs' => 'Wheelchair assistance requested for senior elder',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. Facilities
        DB::table('facilities')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Katra Yatri Registration Counter & RFID Center',
                'category' => 'Registration Counter',
                'circuit' => 'Vaishno Devi',
                'location' => 'Railway Station & Bus Stand, Katra, J&K',
                'latitude' => 32.9928,
                'longitude' => 74.9318,
                'operating_hours' => '24 Hours',
                'contact_number' => '01991-232125',
                'fee_type' => 'Free',
                'description' => 'Official RFID Yatra Parchi issuance center. Mandatory for all pilgrims before starting climb.',
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Banganga Checkpost & Luggage Cloakroom',
                'category' => 'Cloak Room',
                'circuit' => 'Vaishno Devi',
                'location' => 'Banganga Starting Point, Katra',
                'latitude' => 33.0035,
                'longitude' => 74.9450,
                'operating_hours' => '24 Hours',
                'contact_number' => '01991-232029',
                'fee_type' => 'Free / Nominal',
                'description' => 'Secure cloakroom, security screening, and free drinking water refill station.',
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Ardhkuwari Resting Hall & Blanket Counter',
                'category' => 'Resting Camp',
                'circuit' => 'Vaishno Devi',
                'location' => 'Track KM 6, Ardhkuwari Complex',
                'latitude' => 33.0189,
                'longitude' => 74.9542,
                'operating_hours' => '24 Hours',
                'contact_number' => '01991-232888',
                'fee_type' => 'Refundable Security',
                'description' => 'Covered pilgrim shed, free drinking water, hot milk counter, clean restrooms, and sanitized blankets.',
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Shri Mahakaleshwar Pilgrim Facilitation Center',
                'category' => 'Resting Camp',
                'circuit' => 'Ujjain Mahakal',
                'location' => 'Gate No. 4, Mahakal Lok, Ujjain, MP',
                'latitude' => 23.1827,
                'longitude' => 75.7682,
                'operating_hours' => '04:00 AM - 11:00 PM',
                'contact_number' => '0734-2550563',
                'fee_type' => 'Free',
                'description' => 'Air-conditioned waiting lounges, drinking water, prasad counters, and special queue management.',
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 5. Medical Centres
        DB::table('medical_centres')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Shri Mata Vaishno Devi Emergency Hospital',
                'centre_type' => 'Emergency Trauma Hospital',
                'circuit' => 'Vaishno Devi',
                'location' => 'Banganga Road, Katra Base, J&K',
                'latitude' => 33.0012,
                'longitude' => 74.9421,
                'helpline_number' => '01991-232044',
                'doctor_in_charge' => 'Dr. Rajesh Sharma, MD',
                'available_facilities' => 'ICU, Cardiac Life Support, 24x7 Ambulance, Digital X-Ray, Blood Bank',
                'is_24x7' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Ardhkuwari Midpoint Dispensary & Oxygen Booth',
                'centre_type' => 'Dispensary',
                'circuit' => 'Vaishno Devi',
                'location' => 'Ardhkuwari Track KM 6',
                'latitude' => 33.0185,
                'longitude' => 74.9540,
                'helpline_number' => '01991-232190',
                'doctor_in_charge' => 'Dr. Meenakshi Jamwal, MBBS',
                'available_facilities' => 'Oxygen Concentrators, Nebulizers, ECG, Blister care, Pain relief injections',
                'is_24x7' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Mahakaleshwar Emergency First Aid Unit',
                'centre_type' => 'Emergency Hospital',
                'circuit' => 'Ujjain Mahakal',
                'location' => 'Mahakal Mandir Premises, Gate 2, Ujjain',
                'latitude' => 23.1830,
                'longitude' => 75.7680,
                'helpline_number' => '0734-2551444',
                'doctor_in_charge' => 'Dr. Arvind Saxena, MBBS',
                'available_facilities' => 'Paramedic team on site, cardiac defibrillators, heat exhaustion triage, 2 ambulances',
                'is_24x7' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 6. Accommodations
        DB::table('accommodations')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Niharika Yatri Niwas (Shrine Board)',
                'type' => 'Trust Guest House',
                'circuit' => 'Vaishno Devi',
                'address' => 'Railway Road, Katra, Jammu & Kashmir',
                'latitude' => 32.9935,
                'longitude' => 74.9325,
                'contact_phone' => '01991-232029',
                'price_range' => '₹400 - ₹1200 / Room',
                'amenities' => 'Air-cooled rooms, clean attached washrooms, vegetarian Bhojanalaya, lift, 24x7 hot water',
                'booking_url' => 'https://www.maavaishnodevi.org/',
                'rating' => 4.7,
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Shree Mahakal Bhakt Niwas',
                'type' => 'Dharamshala',
                'circuit' => 'Ujjain Mahakal',
                'address' => 'Near Harifatak Overbridge, Ujjain, MP',
                'latitude' => 23.1750,
                'longitude' => 75.7720,
                'contact_phone' => '0734-2550563',
                'price_range' => '₹300 - ₹800 / Room',
                'amenities' => 'AC & Non-AC family rooms, pure sattvic canteen, RO water, spacious parking, lift',
                'booking_url' => 'https://shrimahakaleshwar.com/',
                'rating' => 4.6,
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 7. Announcements
        DB::table('announcements')->insertOrIgnore([
            [
                'id' => 1,
                'title' => 'Pre-Monsoon Weather Advisory & Rain Gear Recommendation',
                'circuit' => 'Vaishno Devi',
                'priority' => 'advisory',
                'content' => 'Light to moderate intermittent showers predicted in the Trikuta hills. Yatris are strongly advised to carry waterproof jackets and non-skid walking shoes.',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'Bhasma Aarti Registration Gate & Pre-Verification Notice',
                'circuit' => 'Ujjain Mahakal',
                'priority' => 'alert',
                'content' => 'Pilgrims with online Bhasma Aarti tokens must report at Gate No. 3 at least 90 minutes prior to entry with original photo identity proof.',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 8. FAQs
        DB::table('faqs')->insertOrIgnore([
            [
                'id' => 1,
                'category' => 'Registration & Yatra Slip',
                'question' => 'Is online registration or RFID Yatra slip mandatory before visiting?',
                'answer' => 'Yes. For shrines like Mata Vaishno Devi, Char Dham, and Amarnath, every pilgrim must carry an official RFID card or registration slip. These are issued free of cost upon showing a valid government photo ID.',
                'hindi_question' => 'क्या यात्रा शुरू करने से पहले ऑनलाइन पंजीकरण या RFID पर्ची अनिवार्य है?',
                'hindi_answer' => 'हाँ। माता वैष्णो देवी, चार धाम और अमरनाथ जैसी यात्राओं के लिए प्रत्येक तीर्थयात्री के पास वैध RFID कार्ड या बायोमेट्रिक पंजीकरण पर्ची होना अनिवार्य है।',
                'display_order' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category' => 'Medical & Fitness',
                'question' => 'What medical support is available for senior citizens or cardiac patients?',
                'answer' => 'Designated medical units and first aid dispensaries with oxygen concentrators, ECG, and emergency paramedics are stationed every 2-3 km along the main routes.',
                'hindi_question' => 'ट्रैक पर वरिष्ठ नागरिकों या हृदय रोगियों के लिए क्या चिकित्सा सहायता उपलब्ध है?',
                'hindi_answer' => 'प्रमुख यात्रा मार्गों पर हर 2-3 किमी की दूरी पर ऑक्सीजन कंसंट्रेटर, ईसीजी और आपातकालीन पैरामेडिक्स से लैस चिकित्सा इकाइयां उपलब्ध हैं।',
                'display_order' => 2,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
