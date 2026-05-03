<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $primaryKey = 'CreditID';

    protected $fillable = [
        'CustomerID',
        'FuelID',
        'Quantity',
        'credit_date',
        'status',   // unpaid | partial | paid
        'archived',
    ];

    protected $casts = [
        'Quantity'    => 'decimal:3',
        'credit_date' => 'date',
        'archived'    => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }

    public function payments()
    {
        return $this->hasMany(CreditPayment::class, 'CreditID', 'CreditID');
    }

    public function salesCredits()
    {
        return $this->hasMany(SalesCredit::class, 'CreditID', 'CreditID');
    }
}