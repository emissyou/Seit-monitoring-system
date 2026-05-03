<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PumpFuel extends Model
{
    protected $primaryKey = 'PumpFuelID';

    protected $fillable = [
        'PumpID',
        'FuelID',
        'totalizer_reading',
    ];

    protected $casts = [
        'totalizer_reading' => 'decimal:3',
    ];

    public function pump()
    {
        return $this->belongsTo(Pump::class, 'PumpID', 'PumpID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }

    public function totalizerLogs()
    {
        return $this->hasMany(TotalizerLog::class, 'PumpFuelID', 'PumpFuelID');
    }
}