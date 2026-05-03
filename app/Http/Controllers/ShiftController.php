<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Fuel;
use App\Models\Pump;
use App\Models\PumpFuel;
use App\Models\Sale;
use App\Models\SalesCredit;
use App\Models\SalesDetail;
use App\Models\SalesDiscount;
use App\Models\Shift;
use App\Models\ShiftReading;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /* ─────────────────────────────────────────────
     |  INDEX  — dashboard + open/close forms
     ───────────────────────────────────────────── */
    public function index(Request $request)
    {
        $view           = $request->get('view', 'home');
        $dateFrom       = $request->get('date_from', Carbon::today()->startOfMonth()->toDateString());
        $dateTo         = $request->get('date_to',   Carbon::today()->toDateString());
        $statusFilter   = $request->get('status',   'all');
        $archivedFilter = $request->get('archived', 'false');

        // Active shift (with readings + nested fuel)
        $activeShift = Shift::with(['shiftReadings.pump', 'shiftReadings.fuel'])
            ->where('status', 'open')
            ->latest('ShiftID')
            ->first();

        $latestClosedShift = Shift::where('status', 'closed')
            ->latest('closed_at')
            ->first();

        // ── Shift history query ──────────────────────────────
        $query = Shift::with(['shiftReadings', 'sales.salesDiscounts', 'sales.salesCredits'])
            ->whereBetween('sales_date', [$dateFrom, $dateTo]);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Using a soft-delete style `archived` boolean column (add to migration if needed)
        // $query->where('archived', $archivedFilter === 'true');

        $shifts = $query->latest('sales_date')->get();

        // ── Aggregate KPI totals ─────────────────────────────
        $totals = [
            'liters'       => $shifts->sum('totalizer_liters'),
            'gross'        => $shifts->sum('computed_gross_sales'),
            'discount'     => $shifts->sum('total_discount'),
            'credit'       => $shifts->sum('total_credit'),
            'net'          => $shifts->sum('computed_net_sales'),
            'cash_in_hand' => $shifts->sum('computed_cash_in_hand'),
        ];

        // ── Fuel performance breakdown ───────────────────────
        // Sums liters & value per fuel type across all shift readings
        $fuelTotals = ShiftReading::with('fuel')
            ->whereIn('ShiftID', $shifts->pluck('ShiftID'))
            ->whereNotNull('closing_reading')
            ->get()
            ->groupBy(fn($r) => $r->fuel->fuel_name ?? 'Unknown')
            ->map(fn($readings) => [
                'liters' => $readings->sum(fn($r) => max(0, $r->closing_reading - $r->opening_reading)),
                'value'  => $readings->sum(fn($r) => max(0, $r->closing_reading - $r->opening_reading) * ($r->price_per_liter ?? 0)),
            ]);

        // ── Dynamic form data ────────────────────────────────
        $pumps     = Pump::with(['pumpFuels.fuel'])->orderBy('pump_name')->get();
        $fuels     = Fuel::orderBy('fuel_name')->get();
        $customers = Customer::orderBy('first_name')->get();

        return view('Shift.index', compact(
            'view',
            'dateFrom',
            'dateTo',
            'statusFilter',
            'archivedFilter',
            'activeShift',
            'latestClosedShift',
            'shifts',
            'totals',
            'fuelTotals',
            'pumps',
            'fuels',
            'customers',
        ));
    }

    /* ─────────────────────────────────────────────
     |  OPEN SHIFT
     |  POST /shift/open
     |  Input: opening_readings[pumpFuelId] = reading
     ───────────────────────────────────────────── */
    public function open(Request $request)
    {
        if (Shift::where('status', 'open')->exists()) {
            return back()->with('error', 'A shift is already open. Close it first.');
        }

        $request->validate([
            'opening_readings'   => 'required|array|min:1',
            'opening_readings.*' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            // 1. Create the Shift record
            $shift = Shift::create([
                'EmployeeID' => auth()->id() ?? null,
                'sales_date' => Carbon::today()->toDateString(),
                'status'     => 'open',
                'opened_at'  => Carbon::now(),
            ]);

            // 2. Create one ShiftReading row per PumpFuel
            foreach ($request->opening_readings as $pumpFuelId => $reading) {
                $pumpFuel = PumpFuel::with('fuel')->findOrFail($pumpFuelId);

                ShiftReading::create([
                    'ShiftID'         => $shift->ShiftID,
                    'PumpID'          => $pumpFuel->PumpID,
                    'FuelID'          => $pumpFuel->FuelID,
                    'opening_reading' => (float) $reading,
                ]);
            }
        });

        return redirect()->route('shift.management', ['view' => 'home'])
                         ->with('success', 'Shift opened successfully.');
    }

    /* ─────────────────────────────────────────────
     |  CLOSE SHIFT
     |  POST /shift/close
     |  Input:
     |    shift_id
     |    closing_readings[pumpFuelId] = reading
     |    prices[fuelId]               = price_per_liter
     |    discounts[]                  = [{fuel_id, customer_id, liters, retail_price, discount_per_liter, discount_sale, description}]
     |    credits[]                    = [{customer_id, liters, retail_price, retail_sale, discounted, discounted_sale, description}]
     ───────────────────────────────────────────── */
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

            $prices      = $request->prices;           // [fuelId => price]
            $closings    = $request->closing_readings; // [pumpFuelId => reading]

            $totalLiters = 0;
            $grossSales  = 0;

            // 1. Update each ShiftReading with closing_reading + price_per_liter
            foreach ($shift->shiftReadings as $sr) {
                // Match by PumpFuelID — the form sends pumpFuelId as the key
                // We need to find the PumpFuel that matches this ShiftReading
                $pumpFuel = PumpFuel::where('PumpID', $sr->PumpID)
                    ->where('FuelID', $sr->FuelID)
                    ->first();

                if (! $pumpFuel) continue;

                $pumpFuelId     = $pumpFuel->getKey();
                $closingReading = (float) ($closings[$pumpFuelId] ?? 0);
                $pricePerLiter  = (float) ($prices[$sr->FuelID] ?? 0);
                $liters         = max(0, $closingReading - $sr->opening_reading);

                $sr->update([
                    'closing_reading' => $closingReading,
                    'price_per_liter' => $pricePerLiter,
                ]);

                $totalLiters += $liters;
                $grossSales  += $liters * $pricePerLiter;
            }

            // 2. Process discounts
            $totalDiscount = 0;
            $discountRows  = $request->input('discounts', []);

            // 3. Process credits
            $totalCredit        = 0;
            $totalRegularCredit = 0;
            $creditRows         = $request->input('credits', []);

            foreach ($creditRows as $c) {
                $isDiscounted = ! empty($c['discounted']);
                $amount       = $isDiscounted
                    ? (float) ($c['discounted_sale'] ?? 0)
                    : (float) ($c['retail_sale'] ?? 0);
                $totalCredit += $amount;
                if (! $isDiscounted) {
                    $totalRegularCredit += $amount;
                }
            }

            foreach ($discountRows as $d) {
                $totalDiscount += (float) ($d['discount_sale'] ?? 0);
            }

            $netSales   = $grossSales - $totalDiscount;
            $cashInHand = $netSales - $totalRegularCredit;

            // 4. Create a Sale summary record (one per pump in the shift)
            //    Group readings by pump
            $pumpGroups = $shift->shiftReadings->groupBy('PumpID');

            foreach ($pumpGroups as $pumpId => $readings) {
                $pumpLiters = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading));
                $pumpGross  = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading) * ($r->price_per_liter ?? 0));

                $sale = Sale::create([
                    'ShiftID'               => $shift->ShiftID,
                    'PumpID'                => $pumpId,
                    'date'                  => $shift->sales_date,
                    'totalizer_liters'      => $pumpLiters,
                    'computed_gross_sales'  => $pumpGross,
                    'total_discount'        => 0, // updated below
                    'total_credit'          => 0,
                    'computed_net_sales'    => $pumpGross,
                    'computed_cash_in_hand' => $pumpGross,
                ]);

                // 4a. Sales Details — one row per fuel reading
                foreach ($readings as $sr) {
                    SalesDetail::create([
                        'SalesID'        => $sale->SalesID,
                        'FuelID'         => $sr->FuelID,
                        'Price_per_Liter'=> $sr->price_per_liter ?? 0,
                        'Liters'         => max(0, ($sr->closing_reading ?? 0) - $sr->opening_reading),
                        'salesDiscount'  => 0,
                    ]);
                }
            }

            // 5. Attach discounts to the first sale (or distribute — simplified here)
            $firstSale = Sale::where('ShiftID', $shift->ShiftID)->first();

            foreach ($discountRows as $d) {
                if ((float)($d['discount_sale'] ?? 0) <= 0) continue;

                SalesDiscount::create([
                    'SalesID'            => $firstSale->SalesID,
                    'FuelID'             => $d['fuel_id'] ?? null,
                    'CustomerID'         => $d['customer_id'] ?? null,
                    'liters'             => (float) ($d['liters'] ?? 0),
                    'retail_price'       => (float) ($d['retail_price'] ?? 0),
                    'discount_per_liter' => (float) ($d['discount_per_liter'] ?? 0),
                    'discount_sale'      => (float) ($d['discount_sale'] ?? 0),
                    'description'        => $d['description'] ?? null,
                ]);
            }

            // 6. Attach credits
            foreach ($creditRows as $c) {
                $isDiscounted = ! empty($c['discounted']);
                if ((float)($c['retail_sale'] ?? 0) <= 0 && (float)($c['discounted_sale'] ?? 0) <= 0) continue;

                SalesCredit::create([
                    'SalesID'         => $firstSale->SalesID,
                    'CustomerID'      => $c['customer_id'] ?? null,
                    'liters'          => (float) ($c['liters'] ?? 0),
                    'retail_price'    => (float) ($c['retail_price'] ?? 0),
                    'retail_sale'     => (float) ($c['retail_sale'] ?? 0),
                    'discounted'      => $isDiscounted,
                    'discounted_sale' => (float) ($c['discounted_sale'] ?? 0),
                    'description'     => $c['description'] ?? null,
                ]);
            }

            // 7. Update sale totals now that discounts/credits are saved
            Sale::where('ShiftID', $shift->ShiftID)->update([
                'total_discount'        => $totalDiscount,
                'total_credit'          => $totalCredit,
                'computed_net_sales'    => $netSales,
                'computed_cash_in_hand' => $cashInHand,
            ]);

            // 8. Close the Shift
            $shift->update([
                'status'    => 'closed',
                'closed_at' => Carbon::now(),
            ]);
        });

        return redirect()->route('shift.management', ['view' => 'home'])
                         ->with('success', 'Shift closed successfully.');
    }

    /* ─────────────────────────────────────────────
     |  SHOW
     ───────────────────────────────────────────── */
    public function show(Shift $shift)
    {
        $shift->load(['shiftReadings.pump', 'shiftReadings.fuel', 'sales.salesDiscounts', 'sales.salesCredits']);
        return view('Shift.show', compact('shift'));
    }

    /* ─────────────────────────────────────────────
     |  EDIT
     ───────────────────────────────────────────── */
    public function edit(Shift $shift)
    {
        return view('Shift.edit', compact('shift'));
    }

    /* ─────────────────────────────────────────────
     |  ARCHIVE / RESTORE / DESTROY
     ───────────────────────────────────────────── */
    public function archive($id)
    {
        // Requires `archived` boolean column in shifts table
        Shift::findOrFail($id)->update(['archived' => true]);
        return response()->json(['success' => true]);
    }

    public function restore($id)
    {
        Shift::findOrFail($id)->update(['archived' => false]);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);

        if ($shift->status === 'open') {
            // Cancel an open shift: delete readings too
            $shift->shiftReadings()->delete();
        }

        $shift->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('shift.management')->with('success', 'Shift deleted.');
    }
}