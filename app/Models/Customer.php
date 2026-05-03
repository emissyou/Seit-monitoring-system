<?php
// FILE: app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'address',
        'status',
    ];

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function creditPayments()
    {
        return $this->hasMany(CreditPayment::class);
    }
}