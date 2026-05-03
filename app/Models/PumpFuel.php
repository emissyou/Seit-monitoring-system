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
        'price_per_liter',
    ];

    protected $casts = [
        'price_per_liter'   => 'float',
        'totalizer_reading' => 'float',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function pump()
    {
        return $this->belongsTo(Pump::class, 'PumpID', 'pumpID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }
}