<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostFoundReport extends Model
{
    protected $fillable = [
        'user_id',
        'report_type',
        'item_name',
        'category',
        'circuit',
        'incident_location',
        'incident_date',
        'description',
        'contact_person',
        'contact_phone',
        'status',
        'is_approved',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
