<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fuel extends Model
{
    protected $primaryKey = 'FuelID';

    protected $fillable = ['fuel_name'];

    public function tanks()
    {
        return $this->hasMany(Tank::class, 'FuelID', 'FuelID');
    }

    public function pumpFuels()
    {
        return $this->hasMany(PumpFuel::class, 'FuelID', 'FuelID');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'FuelID', 'FuelID');
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, 'FuelID', 'FuelID');
    }

    public function shiftReadings()
    {
        return $this->hasMany(ShiftReading::class, 'FuelID', 'FuelID');
    }
}