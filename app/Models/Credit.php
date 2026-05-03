<?php
// FILE: app/Models/Credit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{

    protected $fillable = [
        'customer_id',
        'date',
        'fuel_type',
        'price',
        'liters',
        'amount',
        'balance',
        'payment_status',
        'amount_paid',
        'archived',           // ← Add this
    ];

    protected $casts = [
        'date'           => 'date',
        'price'          => 'decimal:2',
        'liters'         => 'decimal:2',
        'amount'         => 'decimal:2',
        'balance'        => 'decimal:2',
        'amount_paid'    => 'decimal:2',
        'archived'       => 'boolean',     // ← Add this
    ];

    protected $attributes = [
        'amount_paid'    => 0,
        'balance'        => 0,
        'payment_status' => 'unpaid',
        'archived'       => false,         // ← Add this
    ];



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(CreditPayment::class)->orderBy('payment_date', 'asc');
    }

    /**
     * Computed: amount minus amount_paid. Never null.
     */
    public function getRemainingBalanceAttribute(): float
    {
        return round((float) $this->amount - (float) $this->amount_paid, 2);
    }
}