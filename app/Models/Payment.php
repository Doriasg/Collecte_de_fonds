<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'reference',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_url',
        'customer_email',
        'customer_phone',
        'metadata',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['canceled', 'declined']);
    }

    // Méthodes helper
    public function isSuccessful()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function markAsPaid($paymentMethod = null)
    {
        $this->update([
            'status' => 'approved',
            'payment_method' => $paymentMethod,
            'paid_at' => now()
        ]);
    }
}