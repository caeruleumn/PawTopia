<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'member_id',
        'transactable_type',
        'transactable_id',
        'gross_amount',
        'payment_type',
        'transaction_status',
        'snap_token',
        'transaction_id',
        'payment_details',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Get the member that owns the transaction.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the parent transactable model (Booking, Order, etc).
     */
    public function transactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope untuk filter by status
     */
    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    public function scopeSettlement($query)
    {
        return $query->where('transaction_status', 'settlement');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('transaction_status', ['cancel', 'deny', 'expire']);
    }

    /**
     * Check if transaction is paid
     */
    public function isPaid(): bool
    {
        return $this->transaction_status === 'settlement';
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Check if transaction is failed
     */
    public function isFailed(): bool
    {
        return in_array($this->transaction_status, ['cancel', 'deny', 'expire']);
    }

    /**
     * Generate unique order ID
     */
    public static function generateOrderId(): string
    {
        return 'ORDER-' . time() . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid($transactionId = null, $paymentDetails = []): void
    {
        $this->update([
            'transaction_status' => 'settlement',
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'payment_details' => array_merge($this->payment_details ?? [], $paymentDetails),
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed($status = 'cancel', $paymentDetails = []): void
    {
        $this->update([
            'transaction_status' => $status,
            'payment_details' => array_merge($this->payment_details ?? [], $paymentDetails),
        ]);
    }
}
