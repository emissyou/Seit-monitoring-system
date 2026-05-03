<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════
//  Sale  (one per shift — the shift's summary sale record)
// ══════════════════════════════════════════════════════════════
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


// ══════════════════════════════════════════════════════════════
//  SalesDetail  (one row per PumpFuel in the shift)
// ══════════════════════════════════════════════════════════════
class SalesDetail extends Model
{
    protected $table    = 'sales_details';
    protected $primaryKey = 'SaleDetailID';

    protected $fillable = [
        'SalesID',
        'FuelID',
        'salesDiscount',
        'SalesCreditID',
        'Price_per_Liter',
        'Liters',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'SalesID', 'SalesID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }
}


// ══════════════════════════════════════════════════════════════
//  SalesDiscount
// ══════════════════════════════════════════════════════════════
class SalesDiscount extends Model
{
    protected $table      = 'sales_discounts';
    protected $primaryKey = 'salesDiscountID';

    protected $fillable = [
        'SalesID',
        'DiscountID',
        'FuelID',
        'CustomerID',
        'liters',
        'retail_price',
        'discount_per_liter',
        'discount_sale',
        'description',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'SalesID', 'SalesID');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'DiscountID', 'DiscountID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }
}


// ══════════════════════════════════════════════════════════════
//  SalesCredit
// ══════════════════════════════════════════════════════════════
class SalesCredit extends Model
{
    protected $table      = 'sales_credits';
    protected $primaryKey = 'SalesCreditID';

    protected $fillable = [
        'SalesID',
        'CreditID',
        'CustomerID',
        'liters',
        'retail_price',
        'retail_sale',
        'discounted',
        'discounted_sale',
        'description',
    ];

    protected $casts = [
        'discounted' => 'boolean',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'SalesID', 'SalesID');
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class, 'CreditID', 'CreditID');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }
}