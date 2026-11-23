<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = [
        'name',
        'type',
        'circuit',
        'address',
        'latitude',
        'longitude',
        'contact_phone',
        'price_range',
        'amenities',
        'booking_url',
        'rating',
        'is_verified',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'rating' => 'float',
        'is_verified' => 'boolean',
    ];
}
