<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPlace extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'place_type',
        'place_id',
        'place_name',
        'circuit',
        'notes',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
