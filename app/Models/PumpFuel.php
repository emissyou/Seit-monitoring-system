<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PumpFuel extends Model
{
    protected $table    = 'pump_fuels';
    protected $fillable = ['PumpID', 'FuelID', 'totalizer_reading'];

    public function pump() { return $this->belongsTo(Pump::class, 'PumpID'); }
    public function fuel() { return $this->belongsTo(Fuel::class, 'FuelID'); }
    public function totalizerLogs() { return $this->hasMany(TotalizerLog::class, 'PumpFuelID'); }
}