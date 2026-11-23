<?php

namespace App\Services;

class YatraPlannerService
{
    /**
     * Generate customized itinerary based on user preferences and constraints.
     */
    public function generate(array $params): array
    {
        $circuit = $params['circuit'] ?? 'Vaishno Devi';
        $days = max(1, min(7, intval($params['duration_days'] ?? 3)));
        $groupSize = max(1, intval($params['group_size'] ?? 2));
        $budget = floatval($params['budget'] ?? ($days * 2000 * $groupSize));
        $travelMode = $params['travel_mode'] ?? 'train';
        $accessibility = $params['accessibility'] ?? 'standard';

        $itinerary = [
            'circuit' => $circuit,
            'duration_days' => $days,
            'group_size' => $groupSize,
            'travel_mode' => $travelMode,
            'accessibility' => $accessibility,
            'estimated_budget_inr' => $budget,
            'days' => []
        ];

        if ($circuit === 'Vaishno Devi') {
            $itinerary['title'] = "Customized Mata Vaishno Devi Yatra ({$days} Days Plan)";
            $itinerary['days'][] = [
                'day' => 1,
                'theme' => 'Base Camp Arrival & RFID Registration',
                'items' => [
                    [
                        'time' => '07:00 AM - 10:30 AM',
                        'title' => 'Arrival at Katra Railway Station / Bus Stand',
                        'desc' => 'Arrive, freshen up, and proceed to the Yatri Registration Counter for mandatory RFID Yatra Cards.',
                        'location' => 'Katra Base Camp',
                        'cost' => round(150 * $groupSize),
                        'tips' => 'Keep physical Aadhaar cards handy. Biometric RFID card is free and mandatory for all ages.'
                    ],
                    [
                        'time' => '11:00 AM - 01:30 PM',
                        'title' => 'Hotel / Dharamshala Check-in & Rest',
                        'desc' => 'Check in at Niharika Yatri Niwas or nearby verified pilgrim lodge. Store heavy luggage.',
                        'location' => 'Katra City',
                        'cost' => round(600 * $groupSize),
                        'tips' => 'Take light meals. Keep only essential medicine, warm jacket, and walking stick for trek.'
                    ],
                    [
                        'time' => '03:00 PM - 07:30 PM',
                        'title' => 'Trek Commencement via Tarakote Marg',
                        'desc' => ($accessibility === 'elderly' || $accessibility === 'wheelchair')
                            ? 'Begin ascent via modern Tarakote Marg (gentler gradient, smooth surface suitable for battery rickshaws and wheelchairs).'
                            : 'Commence trek from Banganga Checkpost via traditional route or Tarakote Marg.',
                        'location' => 'Banganga to Ardhkuwari Track (6 km)',
                        'cost' => round(200 * $groupSize),
                        'tips' => 'Refill water at free Shrine Board RO points. Take 10-minute breathers every 1.5 km.'
                    ],
                    [
                        'time' => '08:00 PM - 10:00 PM',
                        'title' => 'Night Halt & Rest at Ardhkuwari',
                        'desc' => 'Arrive at Ardhkuwari resting complex. Collect blankets against refundable slip, enjoy hot milk/tea.',
                        'location' => 'Ardhkuwari Midpoint (Elev. 1400m)',
                        'cost' => round(250 * $groupSize),
                        'tips' => 'If Darshan token waiting line is long, proceed towards Sanjichhat or rest until early morning.'
                    ]
                ]
            ];

            if ($days >= 2) {
                $itinerary['days'][] = [
                    'day' => 2,
                    'theme' => 'Holy Cave Darshan & Bhairon Ghati Ropeway',
                    'items' => [
                        [
                            'time' => '06:00 AM - 09:30 AM',
                            'title' => 'Ascent to Bhawan (Battery Car / Trek)',
                            'desc' => ($accessibility === 'elderly' || $accessibility === 'wheelchair')
                                ? 'Board electric battery car at Sanjichhat to Bhawan (approx. 2.5 km smooth path).'
                                : 'Trek through Himkoti scenic route towards Bhawan.',
                            'location' => 'Sanjichhat to Bhawan',
                            'cost' => round(350 * $groupSize),
                            'tips' => 'Battery car is prioritized for senior citizens and medically certified individuals.'
                        ],
                        [
                            'time' => '10:00 AM - 01:30 PM',
                            'title' => 'Locker Deposit & Holy Cave Darshan',
                            'desc' => 'Deposit mobile phones, leather wallets and shoes in free Shrine Board cloakroom. Proceed through security gate for sacred Pindi Darshan.',
                            'location' => 'Mata Vaishno Devi Bhawan',
                            'cost' => 0,
                            'tips' => 'Special queue assistance available for senior citizens and differently abled pilgrims.'
                        ],
                        [
                            'time' => '02:30 PM - 05:00 PM',
                            'title' => 'Bhairon Ghati Passenger Ropeway',
                            'desc' => 'Take the state-of-the-art cable ropeway to Bhairon Temple to complete the traditional pilgrimage.',
                            'location' => 'Bhawan - Bhairon Cable Car',
                            'cost' => round(100 * $groupSize),
                            'tips' => 'Ropeway tickets can be booked on the spot; ride takes under 5 minutes.'
                        ]
                    ]
                ];
            }

            if ($days >= 3) {
                $itinerary['days'][] = [
                    'day' => 3,
                    'theme' => 'Descent, Prasad Collection & Departure',
                    'items' => [
                        [
                            'time' => '07:00 AM - 12:00 PM',
                            'title' => 'Steady Descent to Katra Base',
                            'desc' => 'Descend safely downhill using walking sticks. Stop for breakfast at Shrine Board Bhojanalaya.',
                            'location' => 'Downhill Track to Katra',
                            'cost' => round(180 * $groupSize),
                            'tips' => 'Use knees gently; avoid running or taking shortcuts through steep unpaved slopes.'
                        ],
                        [
                            'time' => '01:00 PM - 04:00 PM',
                            'title' => 'Souvenir Shopping & Return Journey',
                            'desc' => 'Collect dry fruits, walnuts, and blessed prasad packets from certified market counters. Board onward train/bus.',
                            'location' => 'Katra Main Bazaar',
                            'cost' => round(500 * $groupSize),
                            'tips' => 'Check departure platform 45 minutes prior.'
                        ]
                    ]
                ];
            }
        } elseif ($circuit === 'Ujjain Mahakal') {
            $itinerary['title'] = "Spiritual Ujjain Mahakaleshwar & Omkareshwar Jyotirlinga Yatra ({$days} Days Plan)";
            $itinerary['days'][] = [
                'day' => 1,
                'theme' => 'Mahakal Lok & Evening Bhasma Aarti Preparation',
                'items' => [
                    [
                        'time' => '09:00 AM - 12:00 PM',
                        'title' => 'Arrival & Check-in at Ujjain Dharamshala',
                        'desc' => 'Check in at Shree Mahakal Bhakt Niwas or Avantika Yatri Niwas. Freshen up and register for Darshan tokens.',
                        'location' => 'Near Mahakal Mandir, Ujjain',
                        'cost' => round(400 * $groupSize),
                        'tips' => 'Dress code for sanctum sanctorum requires traditional dhoti-kurta for men and saree for women.'
                    ],
                    [
                        'time' => '03:00 PM - 06:30 PM',
                        'title' => 'Exploration of Shri Mahakal Lok Corridor',
                        'desc' => 'Walk through the 900-meter-long Mahakal Lok corridor, witnessing monumental Shiva statues, mural murals, and Rudrasagar lake.',
                        'location' => 'Mahakal Lok Complex',
                        'cost' => 0,
                        'tips' => 'Electric golf carts available for senior citizens and persons with walking difficulties.'
                    ],
                    [
                        'time' => '07:00 PM - 09:30 PM',
                        'title' => 'Sandhya Aarti & Shipra River Ramghat Darshan',
                        'desc' => 'Attend the mesmerizing evening Sandhya Aarti on the holy banks of Shipra river at Ramghat.',
                        'location' => 'Ramghat, Ujjain',
                        'cost' => round(100 * $groupSize),
                        'tips' => 'Mind slippery steps near the water ghat.'
                    ]
                ]
            ];

            if ($days >= 2) {
                $itinerary['days'][] = [
                    'day' => 2,
                    'theme' => 'Early Morning Bhasma Aarti & Harsiddhi Temple',
                    'items' => [
                        [
                            'time' => '02:30 AM - 06:00 AM',
                            'title' => 'Sacred Bhasma Aarti at Mahakaleshwar Jyotirlinga',
                            'desc' => 'Report at Gate No. 3 with original Aadhaar cards for verified entry to the divine morning Bhasma Aarti.',
                            'location' => 'Mahakaleshwar Sanctum Sanctorum',
                            'cost' => round(200 * $groupSize),
                            'tips' => 'Strict security check. Mobile phones, electronic smartwatches, and bags are prohibited.'
                        ],
                        [
                            'time' => '09:00 AM - 01:00 PM',
                            'title' => 'Kal Bhairav & Harsiddhi Shaktipeeth Darshan',
                            'desc' => 'Visit ancient Kal Bhairav temple, Mangalnath (birthplace of Mars in Vedic astrology), and Maa Harsiddhi temple.',
                            'location' => 'Ujjain Temple Circuit',
                            'cost' => round(300 * $groupSize),
                            'tips' => 'E-rickshaws provide fixed-fare circuit travel across temples.'
                        ]
                    ]
                ];
            }
        } else {
            // Generic Pilgrimage Circuit Generator
            $itinerary['title'] = "Comprehensive {$circuit} Pilgrim Itinerary ({$days} Days Plan)";
            for ($d = 1; $d <= $days; $d++) {
                $itinerary['days'][] = [
                    'day' => $d,
                    'theme' => "Day {$d} - Sacred Darshan, Rest & Local Pilgrim Sites",
                    'items' => [
                        [
                            'time' => '06:00 AM - 09:00 AM',
                            'title' => 'Morning Sacred Darshan & Prayers',
                            'desc' => "Visit primary sanctum in {$circuit}. Follow designated queue pathways and security instructions.",
                            'location' => "{$circuit} Main Complex",
                            'cost' => round(150 * $groupSize),
                            'tips' => 'Arrive early to avoid peak noon heat and queue congestion.'
                        ],
                        [
                            'time' => '11:00 AM - 02:00 PM',
                            'title' => 'Satvik Bhojan & Rest at Yatri Shelter',
                            'desc' => 'Enjoy nutritious vegetarian meals at trust bhojanalaya and rest during afternoon hours.',
                            'location' => 'Pilgrim Trust Canteen',
                            'cost' => round(200 * $groupSize),
                            'tips' => 'Stay hydrated with certified safe drinking water.'
                        ],
                        [
                            'time' => '04:00 PM - 07:30 PM',
                            'title' => 'Cultural Heritage Walk & Evening Aarti',
                            'desc' => 'Witness sacred evening chanting and lamp illumination ceremonies.',
                            'location' => 'Temple Ghat & Parikrama Marg',
                            'cost' => round(100 * $groupSize),
                            'tips' => 'Keep emergency contact cards in pockets of children and senior elders.'
                        ]
                    ]
                ];
            }
        }

        return $itinerary;
    }
}
