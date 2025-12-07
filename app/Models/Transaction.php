<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'fedapay_id',
        'reference',
        'customer_id',
        'amount',
        'currency',
        'status',
        'payment_mode',
        'customer_email',
        'customer_phone',
        'description',
        'metadata',
        'approved_at',
        'canceled_at',
        'declined_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'approved_at' => 'datetime',
        'canceled_at' => 'datetime',
        'declined_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', ['approved', 'transferred']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'created']);
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['canceled', 'declined', 'refunded', 'error']);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, ',', ' ') . ' FCFA';
    }

    public function getIsSuccessfulAttribute()
    {
        return in_array($this->status, ['approved', 'transferred']);
    }

    public function getIsPendingAttribute()
    {
        return in_array($this->status, ['pending', 'created']);
    }

    public function getIsFailedAttribute()
    {
        return in_array($this->status, ['canceled', 'declined', 'refunded', 'error']);
    }

    // Méthodes
    public function updateStatus($status, $data = [])
    {
        $updates = ['status' => $status];
        
        if ($status === 'approved') {
            $updates['approved_at'] = now();
        } elseif ($status === 'canceled') {
            $updates['canceled_at'] = now();
        } elseif ($status === 'declined') {
            $updates['declined_at'] = now();
        } elseif ($status === 'refunded') {
            $updates['refunded_at'] = now();
        }
        
        if (!empty($data)) {
            $metadata = $this->metadata ?? [];
            $updates['metadata'] = array_merge($metadata, $data);
        }
        
        return $this->update($updates);
    }
}