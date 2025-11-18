<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'status',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ✅ Pakai phone sebagai "username" untuk login
    public function getAuthIdentifierName()
    {
        return 'phone';
    }

    // Relationship dengan Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Relationship dengan Pet
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    // Get active pets only
    public function activePets()
    {
        return $this->hasMany(Pet::class)->where('is_active', true);
    }
}