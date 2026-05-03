<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Fuel;
use App\Models\PumpFuel;
use App\Models\Sale;

class Pump extends Model
{
    protected $fillable = ['pump_name'];

    public function pumpFuels() { return $this->hasMany(PumpFuel::class, 'PumpID'); }
    public function sales()     { return $this->hasMany(Sale::class, 'PumpID'); }
}