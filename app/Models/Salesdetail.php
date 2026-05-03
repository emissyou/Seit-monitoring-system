<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    protected $table      = 'sales_details';
    protected $primaryKey = 'SaleDetailID';

    protected $fillable = [
        'SalesID',
        'FuelID',
        'salesDiscount',
        'SalesCreditID',
        'Price_per_Liter',
        'Liters',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'SalesID', 'SalesID');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class, 'FuelID', 'FuelID');
    }
}