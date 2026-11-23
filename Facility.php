<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'category',
        'circuit',
        'location',
        'latitude',
        'longitude',
        'operating_hours',
        'contact_number',
        'fee_type',
        'description',
        'is_verified',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_verified' => 'boolean',
    ];
}
