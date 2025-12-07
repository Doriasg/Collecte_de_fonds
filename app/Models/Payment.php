<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'reference',
        'transaction_reference',
        'amount',
        'fees',
        'net_amount',
        'currency',
        'status',
        'payment_method',
        'payment_url',
        'customer_email',
        'customer_phone',
        'metadata',
        'payment_token',
        'paid_at',
        'is_refunded',
        'refunded_at',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fees' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'is_refunded' => 'boolean'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->whereIn('status', ['canceled', 'declined', 'expired']);
    }

    public function scopeRefunded(Builder $query): Builder
    {
        return $query->where('is_refunded', true);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeByCustomer(Builder $query, string $email): Builder
    {
        return $query->where('customer_email', $email);
    }

    // Méthodes helper
    public function isSuccessful(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return in_array($this->status, ['canceled', 'declined', 'expired']);
    }

    public function markAsPaid(string $paymentMethod = null): void
    {
        $this->update([
            'status' => 'approved',
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
            'net_amount' => $this->calculateNetAmount()
        ]);
    }

    public function markAsRefunded(): void
    {
        $this->update([
            'is_refunded' => true,
            'refunded_at' => now()
        ]);
    }

    public function calculateNetAmount(): float
    {
        // Exemple: montant - frais (5%)
        $fees = $this->amount * 0.05;
        $this->fees = $fees;
        return $this->amount - $fees;
    }

    // Accessors
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getFormattedNetAmountAttribute(): string
    {
        return number_format($this->net_amount ?? $this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getFormattedPaidAtAttribute(): ?string
    {
        return $this->paid_at?->format('d/m/Y H:i');
    }

    public function getStatusBadgeAttribute(): array
    {
        $statuses = [
            'approved' => ['class' => 'bg-green-100 text-green-800', 'label' => 'Réussi'],
            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => 'En attente'],
            'canceled' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Annulé'],
            'declined' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Refusé'],
            'expired' => ['class' => 'bg-gray-100 text-gray-800', 'label' => 'Expiré']
        ];

        return $statuses[$this->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'label' => 'Inconnu'];
    }

    public function getPaymentMethodIconAttribute(): string
    {
        $icons = [
            'mobile_money' => '📱',
            'credit_card' => '💳',
            'bank_transfer' => '🏦',
            'wallet' => '👛',
            null => '❓'
        ];

        return $icons[$this->payment_method] ?? '❓';
    }
}