<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'pet_id',
        'service_type',
        'pet_name',
        'pet_type',
        'booking_date',
        'duration_days',
        'booking_time',
        'drop_off_type',
        'pick_up_type',
        'distance_km',
        'base_price',
        'delivery_fee',
        'notes',
        'status',
        'total_price'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'string',
        'duration_days' => 'integer',
        'distance_km' => 'decimal:2',
        'base_price' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    // Relationship dengan Member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Relationship dengan Pet
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    // Relationship dengan Testimonial
    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

    // Relationship dengan Transaction (polymorphic)
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactable');
    }

    /**
     * Calculate total price based on duration and delivery
     */
    public function calculateTotalPrice()
    {
        $total = $this->base_price * $this->duration_days;
        $total += $this->delivery_fee;
        return $total;
    }

    // Scope untuk status booking
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}