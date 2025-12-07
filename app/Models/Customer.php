<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fedapay_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'country',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Relations
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim($this->firstname . ' ' . $this->lastname);
    }

    // Méthodes
    public function totalSpent()
    {
        return $this->transactions()
            ->successful()
            ->sum('amount');
    }

    public function transactionCount()
    {
        return $this->transactions()->count();
    }
}