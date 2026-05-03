<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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