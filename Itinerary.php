<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'destination_circuit',
        'start_date',
        'duration_days',
        'group_size',
        'estimated_budget_inr',
        'travel_mode',
        'accessibility_needs',
        'notes',
        'is_public',
    ];

    protected $casts = [
        'start_date' => 'date',
        'duration_days' => 'integer',
        'group_size' => 'integer',
        'estimated_budget_inr' => 'float',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ItineraryItem::class)->orderBy('day_number')->orderBy('id');
    }
}
