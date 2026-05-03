<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftReading extends Model
{
    protected $primaryKey = 'ShiftReadingID';

    protected $fillable = [
        'ShiftID',
        'PumpID',
        'FuelID',
        'opening_reading',
        'closing_reading',
        'price_per_liter',
    ];

    protected $casts = [
        'opening_reading' => 'float',
        'closing_reading' => 'float',
        'price_per_liter' => 'float',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'ShiftID', 'ShiftID');
    }

    public function pump()
    {
        return $this->belongsTo(Pump::class, 'PumpID', 'pumpID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }

    // ── Computed ───────────────────────────────────────────────

    /**
     * Liters dispensed in this reading (closing - opening).
     */
    public function getLitersAttribute(): float
    {
        return max(0, ($this->closing_reading ?? 0) - $this->opening_reading);
    }

    /**
     * Gross value of this reading.
     */
    public function getGrossValueAttribute(): float
    {
        return $this->liters * ($this->price_per_liter ?? 0);
    }
}