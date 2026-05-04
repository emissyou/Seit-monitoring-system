<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditView extends Model
{
    protected $table      = 'credit_logs_view'; // ← update if your view has a different name
    protected $primaryKey = 'CreditID';
    public    $timestamps = false;
    public    $incrementing = true;

    protected $casts = [
        'credit_date' => 'date',
    ];
}