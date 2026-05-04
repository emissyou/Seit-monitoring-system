<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalizerLog extends Model
{
    protected $table = 'totalizer_logs';

    protected $fillable = [
        'PumpFuelID',
        'reading',
        'date_recorded',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->date_recorded ??= now()->toDateString();
        });
    }
}