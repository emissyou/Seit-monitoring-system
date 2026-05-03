<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $primaryKey = 'CreditID';

    protected $fillable = [
        'CustomerID',
        'FuelID',
        'PumpFuelID',
        'Quantity',
        'price_per_liter',
        'discount_amount',
        'credit_date',
        'status',
        'archived',
    ];

    protected $casts = [
        'Quantity'        => 'decimal:3',
        'price_per_liter' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'credit_date'     => 'date',
        'archived'        => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }

    public function pumpFuel()
    {
        return $this->belongsTo(PumpFuel::class, 'PumpFuelID', 'PumpFuelID');
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