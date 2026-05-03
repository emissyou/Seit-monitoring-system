<?php
// FILE: app/Models/CreditPayment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{
    protected $fillable = [
        'credit_id',
        'customer_id',
        'payment_date',
        'amount_paid',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid'  => 'decimal:2',
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}