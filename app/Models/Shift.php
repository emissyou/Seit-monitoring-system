<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Shift extends Model
{
    protected $primaryKey = 'ShiftID';

    protected $fillable = [
        'EmployeeID',
        'sales_date',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'sales_date' => 'date',
        'opened_at'  => 'datetime',
        'closed_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID', 'EmployeeID');
    }

    public function shiftReadings()
    {
        return $this->hasMany(ShiftReading::class, 'ShiftID', 'ShiftID');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'ShiftID', 'ShiftID');
    }

    // ── Computed Accessors (aggregated from related records) ───

    /**
     * Total liters sold across all readings in this shift.
     */
    public function getTotalizerLitersAttribute(): float
    {
        return $this->shiftReadings
            ->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading));
    }

    /**
     * Gross sales = sum of (liters × price) per reading.
     */
    public function getComputedGrossSalesAttribute(): float
    {
        return $this->shiftReadings
            ->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading) * ($r->price_per_liter ?? 0));
    }

    /**
     * Total discount from all SalesDiscounts linked to this shift's sales.
     */
    public function getTotalDiscountAttribute(): float
    {
        return $this->sales
            ->flatMap(fn($s) => $s->salesDiscounts)
            ->sum('discount_sale');
    }

    /**
     * Total credit from all SalesCredits linked to this shift's sales.
     */
    public function getTotalCreditAttribute(): float
    {
        return $this->sales
            ->flatMap(fn($s) => $s->salesCredits)
            ->sum(fn($c) => $c->discounted ? $c->discounted_sale : $c->retail_sale);
    }

    public function getComputedNetSalesAttribute(): float
    {
        return $this->computed_gross_sales - $this->total_discount;
    }

    public function getComputedCashInHandAttribute(): float
    {
        // Cash = Net - credits that are NOT discounted (discounted credits are considered paid differently)
        $regularCredit = $this->sales
            ->flatMap(fn($s) => $s->salesCredits)
            ->where('discounted', false)
            ->sum('retail_sale');

        return $this->computed_net_sales - $regularCredit;
    }
}