<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $primaryKey = 'DiscountID';

    protected $fillable = [
        'CustomerID',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'description',
        'is_active',
        'archived',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date'     => 'date',
        'end_date'       => 'date',
        'is_active'      => 'boolean',
        'archived'       => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function salesDiscounts()
    {
        return $this->hasMany(SalesDiscount::class, 'DiscountID', 'DiscountID');
    }
}