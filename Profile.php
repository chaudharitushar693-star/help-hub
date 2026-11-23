<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'blood_group',
        'medical_conditions',
        'city',
        'state',
        'accessibility_needs',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
