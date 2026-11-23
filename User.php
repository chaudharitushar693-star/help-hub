<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'email_verified_at',
        'preferred_language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function assistanceRequests()
    {
        return $this->hasMany(AssistanceRequest::class);
    }

    public function savedPlaces()
    {
        return $this->hasMany(SavedPlace::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 1 || ($this->role && $this->role->name === 'admin');
    }

    public function isVolunteer(): bool
    {
        return $this->role_id === 3 || ($this->role && $this->role->name === 'volunteer');
    }
}
