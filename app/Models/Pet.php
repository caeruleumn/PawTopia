<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pet extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'type',
        'breed',
        'age',
        'weight',
        'medical_notes',
        'special_requirements',
        'photo',
        'is_active'
    ];

    protected $casts = [
        'age' => 'integer',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the member that owns the pet
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get bookings for this pet
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope for active pets only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
