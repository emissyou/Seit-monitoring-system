<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['FuelID', 'TankID', 'type', 'current_stock', 'date'];

    protected $casts = ['date' => 'date'];

    public function fuel() { return $this->belongsTo(Fuel::class, 'FuelID'); }
    public function tank() { return $this->belongsTo(Tank::class, 'TankID'); }
}