<?php

namespace App\Http\Controllers;

use App\Models\Pump;
use App\Models\PumpFuel;
use App\Models\Fuel;
use App\Models\Customer;
use App\Models\Shift;
use App\Models\ShiftReading;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\SalesDiscount;
use App\Models\SalesCredit;
use App\Models\Credit;
use App\Models\Discount;
use App\Models\TotalizerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $view         = $request->get('view', 'home');
        $dateFrom     = $request->get('date_from', Carbon::today()->toDateString());
        $dateTo       = $request->get('date_to',   Carbon::today()->toDateString());
        $statusFilter = $request->get('status',   'all');

        $activeShift = Shift::where('status', 'open')
            ->latest('ShiftID')
            ->first();

        if ($activeShift && in_array($view, ['open', 'close'])) {
            $activeShift->load(['shiftReadings.pump', 'shiftReadings.fuel']);
        }

        $latestClosedShift = Shift::where('status', 'closed')
            ->where(function ($q) { $q->where('archived', false)->orWhereNull('archived'); })
            ->latest('closed_at')
            ->first();

        // ── Dashboard: active (non-archived) shifts only ──────────────────
        $query = Shift::whereBetween('sales_date', [$dateFrom, $dateTo])
            ->where(function ($q) {
                $q->where('archived', false)->orWhereNull('archived');
            });

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Latest closed_at first; open shifts (null closed_at) appear at top
        $shifts = $query
            ->orderByRaw('CASE WHEN closed_at IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('closed_at', 'desc')
            ->paginate(7)
            ->withQueryString();

        // ── Archive tab: archived shifts only, latest first, 7/page ───────
        $archiveQuery = Shift::where('archived', true);

        if ($request->filled('archive_from')) {
            $archiveQuery->where('sales_date', '>=', $request->get('archive_from'));
        }
        if ($request->filled('archive_to')) {
            $archiveQuery->where('sales_date', '<=', $request->get('archive_to'));
        }

        $archivedShifts = $archiveQuery
            ->orderBy('closed_at', 'desc')
            ->paginate(7)
            ->withQueryString();

        $shiftIds         = $shifts->pluck('ShiftID');
        $archivedShiftIds = $archivedShifts->pluck('ShiftID');

        $readingTotals = ShiftReading::whereIn('ShiftID', $shiftIds)
            ->whereNotNull('closing_reading')
            ->select(
                'ShiftID',
                DB::raw('SUM(GREATEST(0, closing_reading - opening_reading)) as total_liters'),
                DB::raw('SUM(GREATEST(0, closing_reading - opening_reading) * COALESCE(price_per_liter, 0)) as gross_sales')
            )
            ->groupBy('ShiftID')
            ->get()
            ->keyBy('ShiftID');

        $saleTotals = Sale::whereIn('ShiftID', $shiftIds)
            ->select(
                'ShiftID',
                DB::raw('SUM(total_discount) as total_discount'),
                DB::raw('SUM(total_credit) as total_credit'),
                DB::raw('SUM(computed_net_sales) as net_sales'),
                DB::raw('SUM(computed_cash_in_hand) as cash_in_hand')
            )
            ->groupBy('ShiftID')
            ->get()
            ->keyBy('ShiftID');

        foreach ($shifts as $shift) {
            $r = $readingTotals->get($shift->ShiftID);
            $s = $saleTotals->get($shift->ShiftID);
            $shift->db_liters       = (float) ($r->total_liters  ?? 0);
            $shift->db_gross        = (float) ($r->gross_sales    ?? 0);
            $shift->db_discount     = (float) ($s->total_discount ?? 0);
            $shift->db_credit       = (float) ($s->total_credit   ?? 0);
            $shift->db_net          = (float) ($s->net_sales      ?? 0);
            $shift->db_cash_in_hand = (float) ($s->cash_in_hand   ?? 0);
        }

        // ── db_ values for archived shifts ────────────────────────────────
        $archivedReadingTotals = ShiftReading::whereIn('ShiftID', $archivedShiftIds)
            ->whereNotNull('closing_reading')
            ->select(
                'ShiftID',
                DB::raw('SUM(GREATEST(0, closing_reading - opening_reading)) as total_liters'),
                DB::raw('SUM(GREATEST(0, closing_reading - opening_reading) * COALESCE(price_per_liter, 0)) as gross_sales')
            )
            ->groupBy('ShiftID')
            ->get()
            ->keyBy('ShiftID');

        $archivedSaleTotals = Sale::whereIn('ShiftID', $archivedShiftIds)
            ->select(
                'ShiftID',
                DB::raw('SUM(total_discount) as total_discount'),
                DB::raw('SUM(total_credit) as total_credit'),
                DB::raw('SUM(computed_net_sales) as net_sales'),
                DB::raw('SUM(computed_cash_in_hand) as cash_in_hand')
            )
            ->groupBy('ShiftID')
            ->get()
            ->keyBy('ShiftID');

        foreach ($archivedShifts as $shift) {
            $r = $archivedReadingTotals->get($shift->ShiftID);
            $s = $archivedSaleTotals->get($shift->ShiftID);
            $shift->db_liters       = (float) ($r->total_liters  ?? 0);
            $shift->db_gross        = (float) ($r->gross_sales    ?? 0);
            $shift->db_discount     = (float) ($s->total_discount ?? 0);
            $shift->db_credit       = (float) ($s->total_credit   ?? 0);
            $shift->db_net          = (float) ($s->net_sales      ?? 0);
            $shift->db_cash_in_hand = (float) ($s->cash_in_hand   ?? 0);
        }

        $allReadings = ShiftReading::whereIn('ShiftID', $shiftIds)
            ->whereNotNull('closing_reading')
            ->selectRaw('SUM(GREATEST(0, closing_reading - opening_reading)) as total_liters,
                         SUM(GREATEST(0, closing_reading - opening_reading) * COALESCE(price_per_liter, 0)) as gross_sales')
            ->first();

        $allSales = Sale::whereIn('ShiftID', $shiftIds)
            ->selectRaw('SUM(total_discount) as total_discount,
                         SUM(total_credit) as total_credit,
                         SUM(computed_net_sales) as net_sales,
                         SUM(computed_cash_in_hand) as cash_in_hand')
            ->first();

        $totals = [
            'liters'       => (float) ($allReadings->total_liters ?? 0),
            'gross'        => (float) ($allReadings->gross_sales  ?? 0),
            'discount'     => (float) ($allSales->total_discount  ?? 0),
            'credit'       => (float) ($allSales->total_credit    ?? 0),
            'net'          => (float) ($allSales->net_sales       ?? 0),
            'cash_in_hand' => (float) ($allSales->cash_in_hand    ?? 0),
        ];

        $fuelTotals = ShiftReading::with('fuel:FuelID,fuel_name')
            ->whereIn('ShiftID', $shiftIds)
            ->whereNotNull('closing_reading')
            ->select('FuelID',
                DB::raw('SUM(GREATEST(0, closing_reading - opening_reading)) as liters'),
                DB::raw('SUM(GREATEST(0, closing_reading - opening_reading) * COALESCE(price_per_liter,0)) as value')
            )
            ->groupBy('FuelID')
            ->get()
            ->mapWithKeys(fn($r) => [
                ($r->fuel->fuel_name ?? 'Unknown') => [
                    'liters' => (float) $r->liters,
                    'value'  => (float) $r->value,
                ]
            ]);

        // Load pumps with pumpFuels including price_per_liter for the close-shift form
        $pumps     = Pump::with(['pumpFuels.fuel'])->orderBy('pump_name')->get();
        $fuels     = Fuel::orderBy('fuel_name')->get();
        $customers = Customer::orderBy('First_name')->get();

        return view('Shift.index', compact(
            'view', 'dateFrom', 'dateTo', 'statusFilter',
            'activeShift', 'latestClosedShift', 'shifts', 'archivedShifts', 'totals',
            'fuelTotals', 'pumps', 'fuels', 'customers'
        ));
    }

    // ====================== OPEN SHIFT ======================
    public function open(Request $request)
    {
        if (Shift::where('status', 'open')->exists()) {
            return back()->with('error', 'A shift is already open. Close it first.');
        }

        $request->validate([
            'opening_readings'   => 'required|array|min:1',
            'opening_readings.*' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $shift = Shift::create([
                    'EmployeeID' => auth()->id() ?? null,
                    'sales_date' => Carbon::today()->toDateString(),
                    'status'     => 'open',
                    'opened_at'  => Carbon::now(),
                    'archived'   => false,
                ]);

                foreach ($request->opening_readings as $pumpFuelId => $reading) {
                    $pumpFuel = PumpFuel::findOrFail($pumpFuelId);
                    ShiftReading::create([
                        'ShiftID'         => $shift->ShiftID,
                        'PumpID'          => $pumpFuel->PumpID,
                        'FuelID'          => $pumpFuel->FuelID,
                        'opening_reading' => (float) $reading,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to open shift: ' . $e->getMessage());
        }

        return redirect()->route('shift.management', ['view' => 'home'])
                         ->with('success', 'Shift opened successfully.');
    }

    // ====================== CLOSE SHIFT ======================
    public function close(Request $request)
    {
        $request->validate([
            'shift_id'           => 'required|exists:shifts,ShiftID',
            'closing_readings'   => 'required|array|min:1',
            'closing_readings.*' => 'required|numeric|min:0',
            // prices are now optional overrides; auto-filled from pump_fuels
            'prices'             => 'nullable|array',
            'prices.*'           => 'nullable|numeric|min:0',
        ]);

        $shift = Shift::with('shiftReadings')->findOrFail($request->shift_id);

        if ($shift->status !== 'open') {
            return back()->with('error', 'This shift is not open.');
        }

        DB::transaction(function () use ($request, $shift) {
            $prices   = $request->prices ?? [];
            $closings = $request->closing_readings;

            // Step 1: Update Shift Readings
            foreach ($shift->shiftReadings as $reading) {
                $pumpFuel = PumpFuel::where('PumpID', $reading->PumpID)
                                    ->where('FuelID', $reading->FuelID)
                                    ->first();
                if (!$pumpFuel) continue;

                $pumpFuelId     = $pumpFuel->PumpFuelID;
                $closingReading = (float) ($closings[$pumpFuelId] ?? 0);

                // ─── KEY CHANGE ───────────────────────────────────────────────
                // Price comes from the form override (prices[PumpFuelID]) if provided,
                // otherwise falls back to the stored price on the pump_fuels record.
                $pricePerLiter = isset($prices[$pumpFuelId]) && $prices[$pumpFuelId] !== ''
                    ? (float) $prices[$pumpFuelId]
                    : (float) ($pumpFuel->price_per_liter ?? 0);
                // ─────────────────────────────────────────────────────────────

                $reading->update([
                    'closing_reading' => $closingReading,
                    'price_per_liter' => $pricePerLiter,
                ]);

                $reading->refresh();

                if ($closingReading > 0) {
                    $pumpFuel->update(['totalizer_reading' => $closingReading]);

                    TotalizerLog::create([
                        'PumpFuelID'    => $pumpFuel->PumpFuelID,
                        'reading'       => $closingReading,
                        'date_recorded' => $shift->sales_date,
                    ]);
                }
            }

            // Step 2: Create Sales
            $discountRows = $request->input('discounts', []);
            $creditRows   = $request->input('credits', []);

            $saleByPump = [];
            foreach ($shift->shiftReadings->groupBy('PumpID') as $pumpId => $readings) {
                $pumpLiters = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading));
                $pumpGross  = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading) * ($r->price_per_liter ?? 0));

                $sale = Sale::create([
                    'ShiftID'               => $shift->ShiftID,
                    'PumpID'                => $pumpId,
                    'date'                  => $shift->sales_date,
                    'totalizer_liters'      => $pumpLiters,
                    'computed_gross_sales'  => $pumpGross,
                    'total_discount'        => 0,
                    'total_credit'          => 0,
                    'computed_net_sales'    => $pumpGross,
                    'computed_cash_in_hand' => $pumpGross,
                ]);

                $saleByPump[$pumpId] = $sale;

                foreach ($readings as $sr) {
                    SalesDetail::create([
                        'SalesID'         => $sale->SalesID,
                        'FuelID'          => $sr->FuelID,
                        'Price_per_Liter' => $sr->price_per_liter ?? 0,
                        'Liters'          => max(0, ($sr->closing_reading ?? 0) - $sr->opening_reading),
                        'salesDiscount'   => 0,
                    ]);
                }
            }

            $firstSale = collect($saleByPump)->first();

            // Discounts
            foreach ($discountRows as $d) {
                if ((float)($d['discount_sale'] ?? 0) <= 0) continue;

                $discount = Discount::create([
                    'CustomerID'     => $d['customer_id'] ?? null,
                    'discount_type'  => 'per_liter',
                    'discount_value' => (float)($d['discount_per_liter'] ?? 0),
                    'start_date'     => $shift->sales_date,
                    'end_date'       => $shift->sales_date,
                    'description'    => $d['description'] ?? null,
                    'is_active'      => false,
                    'archived'       => false,
                ]);

                SalesDiscount::create([
                    'SalesID'            => $firstSale->SalesID,
                    'DiscountID'         => $discount->DiscountID,
                    'FuelID'             => $d['fuel_id'] ?? null,
                    'CustomerID'         => $d['customer_id'] ?? null,
                    'liters'             => (float)($d['liters'] ?? 0),
                    'retail_price'       => (float)($d['retail_price'] ?? 0),
                    'discount_per_liter' => (float)($d['discount_per_liter'] ?? 0),
                    'discount_sale'      => (float)($d['discount_sale'] ?? 0),
                    'description'        => $d['description'] ?? null,
                ]);
            }

            // Credits
            foreach ($creditRows as $c) {
                if ((float)($c['retail_sale'] ?? 0) <= 0 && (float)($c['discounted_sale'] ?? 0) <= 0) {
                    continue;
                }

                $customerId = $c['customer_id'] ?? null;
                $fuelId     = $c['fuel_id'] ?? null;
                $liters     = (float)($c['liters'] ?? 0);

                if (!$customerId || !$fuelId || $liters <= 0) {
                    continue;
                }

                // Look up PumpFuel to get price_per_liter at time of credit
                $pumpFuelRecord = PumpFuel::where('FuelID', $fuelId)->first();
                $retailPrice    = (float)($c['retail_price'] ?? $pumpFuelRecord?->price_per_liter ?? 0);

                $credit = Credit::create([
                    'CustomerID'      => $customerId,
                    'FuelID'          => $fuelId,
                    'PumpFuelID'      => $pumpFuelRecord?->PumpFuelID ?? null,
                    'Quantity'        => $liters,
                    'price_per_liter' => $retailPrice,
                    'discount_amount' => 0,
                    'credit_date'     => $shift->sales_date,
                    'status'          => 'unpaid',
                    'archived'        => false,
                ]);

                SalesCredit::create([
                    'SalesID'         => $firstSale->SalesID,
                    'CreditID'        => $credit->CreditID,
                    'CustomerID'      => $customerId,
                    'liters'          => $liters,
                    'retail_price'    => (float)($c['retail_price'] ?? 0),
                    'retail_sale'     => (float)($c['retail_sale'] ?? 0),
                    'discounted'      => !empty($c['discounted']),
                    'discounted_sale' => (float)($c['discounted_sale'] ?? 0),
                    'description'     => $c['description'] ?? null,
                ]);
            }

            // Close the shift
            $shift->update(['status' => 'closed', 'closed_at' => Carbon::now()]);
        });

        return redirect()->route('shift.management', ['view' => 'home'])
                         ->with('success', 'Shift closed successfully.');
    }

    public function show(Shift $shift)
    {
        $shift->load(['shiftReadings.pump', 'shiftReadings.fuel', 'sales.salesDiscounts', 'sales.salesCredits']);
        return view('Shift.show', compact('shift'));
    }

    public function edit(Shift $shift)
    {
        $shift->load(['shiftReadings.pump', 'shiftReadings.fuel']);
        $pumps     = Pump::with(['pumpFuels.fuel'])->orderBy('pump_name')->get();
        $fuels     = Fuel::orderBy('fuel_name')->get();
        $customers = Customer::orderBy('First_name')->get();
        return view('Shift.edit', compact('shift', 'pumps', 'fuels', 'customers'));
    }

    public function archive($id)
    {
        $shift = Shift::findOrFail($id);
        if ($shift->status === 'open') {
            return redirect()->route('shift.management', ['view' => 'home'])
                ->with('error', 'Cannot archive an open shift. Close it first.');
        }
        $shift->update(['archived' => true]);
        return redirect()->route('shift.management', ['view' => 'home'])
            ->with('success', 'Shift archived successfully.');
    }

    public function restore($id)
    {
        Shift::findOrFail($id)->update(['archived' => false]);
        return redirect()->route('shift.management', ['view' => 'archive'])
            ->with('success', 'Shift restored successfully.');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        if ($shift->status === 'open') {
            $shift->shiftReadings()->delete();
        }
        $shift->delete();
        return redirect()->route('shift.management', ['view' => 'archive'])
            ->with('success', 'Shift deleted successfully.');
    }
}