<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCentre extends Model
{
    protected $fillable = [
        'name',
        'centre_type',
        'circuit',
        'location',
        'latitude',
        'longitude',
        'helpline_number',
        'doctor_in_charge',
        'available_facilities',
        'is_24x7',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_24x7' => 'boolean',
    ];
}
