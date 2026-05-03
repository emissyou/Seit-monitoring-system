<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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