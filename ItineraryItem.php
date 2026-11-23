<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryItem extends Model
{
    protected $fillable = [
        'itinerary_id',
        'day_number',
        'time_slot',
        'activity_title',
        'activity_description',
        'location',
        'estimated_cost',
        'tips',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'estimated_cost' => 'float',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
