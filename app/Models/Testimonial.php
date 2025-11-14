<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'booking_id',
        'rating',
        'message'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship with Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relationship with Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope untuk testimonial dengan rating tinggi
     */
    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope untuk testimonial terbaru
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }
}
