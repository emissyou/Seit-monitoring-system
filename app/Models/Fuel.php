<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    protected $fillable = ['fuel_name'];

    public function tanks()       { return $this->hasMany(Tank::class, 'FuelID'); }
    public function pumpFuels()   { return $this->hasMany(PumpFuel::class, 'FuelID'); }
    public function stocks()      { return $this->hasMany(Stock::class, 'FuelID'); }
    public function credits()     { return $this->hasMany(Credit::class, 'FuelID'); }
}