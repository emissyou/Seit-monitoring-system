<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalizerLog extends Model
{
    protected $table    = 'totalizer_logs';
    protected $fillable = ['PumpFuelID', 'reading', 'date_recorded'];

    protected $casts = ['date_recorded' => 'date'];

    public function pumpFuel() { return $this->belongsTo(PumpFuel::class, 'PumpFuelID'); }
}