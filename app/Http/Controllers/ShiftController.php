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
        $view           = $request->get('view', 'home');
        $dateFrom       = $request->get('date_from', Carbon::today()->toDateString());
        $dateTo         = $request->get('date_to',   Carbon::today()->toDateString());
        $statusFilter   = $request->get('status',   'all');
        $archivedFilter = $request->get('archived', 'false');

        // ── Active shift — load readings only when needed ─────────────────
        $activeShift = Shift::where('status', 'open')
            ->latest('ShiftID')
            ->first();

        if ($activeShift && in_array($view, ['open', 'close'])) {
            $activeShift->load(['shiftReadings.pump', 'shiftReadings.fuel']);
        }

        $latestClosedShift = Shift::where('status', 'closed')
            ->latest('closed_at')
            ->first();

        // ── Shift list — NO eager loading at all ──────────────────────────
        $query = Shift::whereBetween('sales_date', [$dateFrom, $dateTo]);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($archivedFilter === 'true') {
            $query->where('archived', true);
        } else {
            $query->where(function ($q) {
                $q->where('archived', false)->orWhereNull('archived');
            });
        }

        $shifts = $query->latest('sales_date')->paginate(15);

        // ── Per-shift totals via DB aggregates (no PHP loops) ─────────────
        $shiftIds = $shifts->pluck('ShiftID');

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

        // ── KPI summary totals ────────────────────────────────────────────
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

        // ── Fuel performance ──────────────────────────────────────────────
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

        $pumps     = Pump::with(['pumpFuels.fuel'])->orderBy('pump_name')->get();
        $fuels     = Fuel::orderBy('fuel_name')->get();
        $customers = Customer::orderBy('First_name')->get();

        return view('Shift.index', compact(
            'view', 'dateFrom', 'dateTo', 'statusFilter', 'archivedFilter',
            'activeShift', 'latestClosedShift', 'shifts', 'totals',
            'fuelTotals', 'pumps', 'fuels', 'customers'
        ));
    }

    // ====================== OPEN SHIFT ======================
    public function open(Request $request)
    {
        if (Shift::where('status', 'open')->exists()) {
            return back()->with('error', 'A shift is already open. Close it first.');
        }

        // Validate — min:0.001 prevents submitting all-zero readings silently
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
            'prices'             => 'required|array',
            'prices.*'           => 'required|numeric|min:0',
        ]);

        $shift = Shift::with('shiftReadings')->findOrFail($request->shift_id);

        if ($shift->status !== 'open') {
            return back()->with('error', 'This shift is not open.');
        }

        DB::transaction(function () use ($request, $shift) {
            $prices   = $request->prices;
            $closings = $request->closing_readings;

            // ── Step 1: Update each ShiftReading with closing_reading & price ──
            // After update(), refresh() the model so PHP sees the new values.
            // Without refresh(), the in-memory collection still has closing_reading = null
            // and every liters/gross calculation below computes zero.
            foreach ($shift->shiftReadings as $reading) {
                $pumpFuel = PumpFuel::where('PumpID', $reading->PumpID)
                                    ->where('FuelID', $reading->FuelID)
                                    ->first();
                if (!$pumpFuel) continue;

                $pumpFuelId     = $pumpFuel->PumpFuelID;
                $closingReading = (float) ($closings[$pumpFuelId] ?? 0);
                $pricePerLiter  = (float) ($prices[$reading->FuelID] ?? 0);

                $reading->update([
                    'closing_reading' => $closingReading,
                    'price_per_liter' => $pricePerLiter,
                ]);

                // FIX: refresh so subsequent reads on this model use the saved values
                $reading->refresh();

                // ── Update pump_fuels.totalizer_reading with the closing value ──
                // This is what the open shift form uses as the "last reading" hint.
                // Also log it to totalizer_logs for a full history of readings.
                if ($closingReading > 0) {
                    $pumpFuel->update(['totalizer_reading' => $closingReading]);

                    TotalizerLog::create([
                        'PumpFuelID'    => $pumpFuel->PumpFuelID,
                        'reading'       => $closingReading,
                        'date_recorded' => $shift->sales_date,
                    ]);
                }
            }

            // ── Step 2: Build per-pump Sales records from the now-fresh readings ──
            $discountRows = $request->input('discounts', []);
            $creditRows   = $request->input('credits', []);

            // Group readings by pump and create one Sale per pump
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

            // ── Step 3: Attach discounts & credits ────────────────────────────
            // ERD flow:
            //   discounts  →  sales_discounts (DiscountID FK)
            //   credits    →  sales_credits   (CreditID FK)
            // So we create the parent record first, get its ID, then create the
            // sales child record linked to both the Sale and the parent record.
            $firstSale = collect($saleByPump)->first();

            foreach ($discountRows as $d) {
                if ((float)($d['discount_sale'] ?? 0) <= 0) continue;

                // 1. Create the Discount master record
                $discount = Discount::create([
                    'CustomerID'     => $d['customer_id'] ?? null,
                    'discount_type'  => 'per_liter',
                    'discount_value' => (float)($d['discount_per_liter'] ?? 0),
                    'start_date'     => $shift->sales_date,
                    'end_date'       => $shift->sales_date,
                    'description'    => $d['description'] ?? null,
                    'is_active'      => false, // shift-close discounts are historical, not ongoing
                    'archived'       => false,
                ]);

                // 2. Create the SalesDiscount linking Sale + Discount
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

            foreach ($creditRows as $c) {
                if ((float)($c['retail_sale'] ?? 0) <= 0 && (float)($c['discounted_sale'] ?? 0) <= 0) continue;

                // Require customer and fuel for the credits ledger entry
                $customerId = $c['customer_id'] ?? null;
                $fuelId     = $c['fuel_id'] ?? null;

                // 1. Create the Credit master record (ledger entry)
                $credit = Credit::create([
                    'CustomerID'  => $customerId,
                    'FuelID'      => $fuelId,
                    'Quantity'    => (float)($c['liters'] ?? 0),
                    'credit_date' => $shift->sales_date,
                    'status'      => 'unpaid',
                    'archived'    => false,
                ]);

                // 2. Create the SalesCredit linking Sale + Credit
                SalesCredit::create([
                    'SalesID'         => $firstSale->SalesID,
                    'CreditID'        => $credit->CreditID,
                    'CustomerID'      => $customerId,
                    'liters'          => (float)($c['liters'] ?? 0),
                    'retail_price'    => (float)($c['retail_price'] ?? 0),
                    'retail_sale'     => (float)($c['retail_sale'] ?? 0),
                    'discounted'      => !empty($c['discounted']),
                    'discounted_sale' => (float)($c['discounted_sale'] ?? 0),
                    'description'     => $c['description'] ?? null,
                ]);
            }

            // ── Step 4: Close the shift (triggers trg_after_shift_close) ──────
            // NOTE: Do NOT manually update sales totals here.
            // The SQL trigger chain already owns all recalculation:
            //   trg_after_shift_reading_update  → sets gross/net/cash per sale row
            //   trg_after_sales_discount_insert → adjusts net/cash after each discount insert
            //   trg_after_sales_credit_insert   → adjusts cash after each credit insert
            // A bulk Sale::update() here would overwrite trigger results with wrong
            // shift-level totals applied to every pump's sale, doubling the figures.
            $shift->update(['status' => 'closed', 'closed_at' => Carbon::now()]);
        });

        return redirect()->route('shift.management', ['view' => 'home'])
                         ->with('success', 'Shift closed successfully.');
    }

    // ====================== SHOW SHIFT ======================
    public function show(Shift $shift)
    {
        $shift->load(['shiftReadings.pump', 'shiftReadings.fuel', 'sales.salesDiscounts', 'sales.salesCredits']);
        return view('Shift.show', compact('shift'));
    }

    // ====================== EDIT SHIFT ======================
    public function edit(Shift $shift)
    {
        $shift->load(['shiftReadings.pump', 'shiftReadings.fuel']);
        $pumps     = Pump::with(['pumpFuels.fuel'])->orderBy('pump_name')->get();
        $fuels     = Fuel::orderBy('fuel_name')->get();
        $customers = Customer::orderBy('First_name')->get();
        return view('Shift.edit', compact('shift', 'pumps', 'fuels', 'customers'));
    }

    // ====================== ARCHIVE SHIFT ======================
    public function archive($id)
    {
        $shift = Shift::findOrFail($id);
        if ($shift->status === 'open') {
            return back()->with('error', 'Cannot archive an open shift. Close it first.');
        }
        $shift->update(['archived' => true]);
        return back()->with('success', 'Shift archived successfully.');
    }

    // ====================== RESTORE SHIFT ======================
    public function restore($id)
    {
        Shift::findOrFail($id)->update(['archived' => false]);
        return back()->with('success', 'Shift restored successfully.');
    }

    // ====================== DELETE SHIFT ======================
    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        if ($shift->status === 'open') {
            $shift->shiftReadings()->delete();
        }
        $shift->delete();
        return back()->with('success', 'Shift deleted successfully.');
    }
}