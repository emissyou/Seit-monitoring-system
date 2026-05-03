<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    protected $fillable = ['FuelID', 'max_quantity', 'current_stock'];

    public function fuel()   { return $this->belongsTo(Fuel::class, 'FuelID'); }
    public function stocks() { return $this->hasMany(Stock::class, 'TankID'); }
}