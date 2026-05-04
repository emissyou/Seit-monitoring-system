<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountView extends Model
{
    protected $table      = 'discount_logs_view';
    protected $primaryKey = 'DiscountID';
    public    $timestamps = false;
    public    $incrementing = true;

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];
}