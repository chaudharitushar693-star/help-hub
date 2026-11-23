<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'pilgrim_name',
        'email',
        'circuit',
        'rating',
        'service_aspect',
        'comment',
        'is_moderated',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_moderated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
