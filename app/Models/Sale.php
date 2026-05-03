<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $primaryKey = 'SalesID';

    protected $fillable = [
        'ShiftID',
        'PumpID',
        'date',
        'totalizer_liters',
        'computed_gross_sales',
        'total_discount',
        'total_credit',
        'computed_net_sales',
        'computed_cash_in_hand',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'ShiftID', 'ShiftID');
    }

    public function pump()
    {
        return $this->belongsTo(Pump::class, 'PumpID', 'pumpID');
    }

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'SalesID', 'SalesID');
    }

    public function salesDiscounts()
    {
        return $this->hasMany(SalesDiscount::class, 'SalesID', 'SalesID');
    }

    public function salesCredits()
    {
        return $this->hasMany(SalesCredit::class, 'SalesID', 'SalesID');
    }
}