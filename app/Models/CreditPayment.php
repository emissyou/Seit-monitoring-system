<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{
    protected $fillable = [
        'CreditID',    // fixed: matches Credit primary key
        'CustomerID',  // fixed: matches Customer primary key
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
        return $this->belongsTo(Credit::class, 'CreditID', 'CreditID');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }
}