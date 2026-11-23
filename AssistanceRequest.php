<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistanceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_code',
        'request_type',
        'priority',
        'pilgrim_name',
        'contact_number',
        'alternate_contact',
        'circuit',
        'current_location',
        'latitude',
        'longitude',
        'description',
        'status',
        'assigned_to',
        'resolution_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updates()
    {
        return $this->hasMany(RequestUpdate::class)->orderBy('created_at', 'desc');
    }

    public static function generateTrackingCode(): string
    {
        return 'YS-REQ-' . date('Ym') . '-' . mt_rand(1000, 9999);
    }
}
