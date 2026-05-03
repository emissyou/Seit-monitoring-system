<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'customer_id',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'description',
        'archived',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'discount_value' => 'decimal:2',
        'archived'       => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isActive()
    {
        $today = now()->toDateString();
        return !$this->archived && 
               $today >= $this->start_date->toDateString() && 
               $today <= $this->end_date->toDateString();
    }
}