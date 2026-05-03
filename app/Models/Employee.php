<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'EmployeeID';

    protected $fillable = [
        'role',
        'First_name',
        'Middle_name',
        'Last_name',
        'contact_number',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class, 'EmployeeID', 'EmployeeID');
    }
}