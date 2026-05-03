<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'CustomerID';

    protected $fillable = [
        'First_name',
        'Middle_name',
        'Last_name',
        'contact_number',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function credits()
    {
        return $this->hasMany(Credit::class, 'CustomerID', 'CustomerID');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class, 'CustomerID', 'CustomerID');
    }

    public function salesCredits()
    {
        return $this->hasMany(SalesCredit::class, 'CustomerID', 'CustomerID');
    }
}