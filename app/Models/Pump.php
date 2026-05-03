<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pump extends Model
{
    protected $primaryKey = 'pumpID';

    protected $fillable = ['pump_name'];

    public function pumpFuels()
    {
        return $this->hasMany(PumpFuel::class, 'PumpID', 'pumpID');
    }

    public function shiftReadings()
    {
        return $this->hasMany(ShiftReading::class, 'PumpID', 'pumpID');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'PumpID', 'pumpID');
    }
}