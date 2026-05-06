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
        $dateFrom     = $request->get('date_from', '');
        $dateTo       = $request->get('date_to',   '');
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

        $query = Shift::where(function ($q) {
                $q->where('archived', false)->orWhereNull('archived');
            });

        if ($dateFrom !== '') {
            $query->where('sales_date', '>=', $dateFrom);
        }
        if ($dateTo !== '') {
            $query->where('sales_date', '<=', $dateTo);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $shifts = $query
            ->orderByRaw('CASE WHEN closed_at IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('closed_at', 'desc')
            ->paginate(7)
            ->withQueryString();

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

            // ── Step 1: Update Shift Readings ─────────────────────────────
            foreach ($shift->shiftReadings as $reading) {
                $pumpFuel = PumpFuel::where('PumpID', $reading->PumpID)
                                    ->where('FuelID', $reading->FuelID)
                                    ->first();
                if (!$pumpFuel) continue;

                $pumpFuelId    = $pumpFuel->PumpFuelID;
                $closingReading = (float) ($closings[$pumpFuelId] ?? 0);
                $pricePerLiter  = isset($prices[$pumpFuelId]) && $prices[$pumpFuelId] !== ''
                    ? (float) $prices[$pumpFuelId]
                    : (float) ($pumpFuel->price_per_liter ?? 0);

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

            // ── Step 2: Collect discount and credit rows from the form ────
            $discountRows = $request->input('discounts', []);
            $creditRows   = $request->input('credits', []);

            // ── Step 3: Create Sales per pump ─────────────────────────────
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
                    'total_discount'        => 0,   // will be set below
                    'total_credit'          => 0,   // will be set below
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

            // ── Step 4: Insert Discounts ──────────────────────────────────
            // (replaces Trigger 2 — we collect all discount_sale values here
            //  so we can compute the totals ourselves without needing a trigger)
            $totalDiscountAmount = 0;

            foreach ($discountRows as $d) {
                $discountSale = (float)($d['discount_sale'] ?? 0);
                if ($discountSale <= 0 || !$firstSale) continue;

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
                    'FuelID'             => $d['fuel_id']          ?? null,
                    'CustomerID'         => $d['customer_id']       ?? null,
                    'liters'             => (float)($d['liters']             ?? 0),
                    'retail_price'       => (float)($d['retail_price']       ?? 0),
                    'discount_per_liter' => (float)($d['discount_per_liter'] ?? 0),
                    'discount_sale'      => $discountSale,
                    'description'        => $d['description'] ?? null,
                ]);

                $totalDiscountAmount += $discountSale;
            }

            // ── Step 5: Insert Credits ────────────────────────────────────
            // (replaces Trigger 1 — we collect retail_sale and discounted_sale
            //  values here so we can compute cash in hand ourselves)
            $totalCreditAmount        = 0;   // all credits (affects total_credit column)
            $totalNonDiscountedCredit = 0;   // only non-discounted (affects cash in hand)

            foreach ($creditRows as $c) {
                $retailSale     = (float)($c['retail_sale']     ?? 0);
                $discountedSale = (float)($c['discounted_sale'] ?? 0);
                $isDiscounted   = !empty($c['discounted']);

                if ($retailSale <= 0 && $discountedSale <= 0) continue;

                $customerId = $c['customer_id'] ?? null;
                $fuelId     = $c['fuel_id']     ?? null;
                $liters     = (float)($c['liters'] ?? 0);

                if (!$customerId || !$fuelId || $liters <= 0 || !$firstSale) continue;

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
                    'retail_price'    => $retailPrice,
                    'retail_sale'     => $retailSale,
                    'discounted'      => $isDiscounted,
                    'discounted_sale' => $discountedSale,
                    'description'     => $c['description'] ?? null,
                ]);

                // What reduces cash in hand: only non-discounted credits use retail_sale
                // Discounted credits use discounted_sale for total_credit column
                $totalCreditAmount        += $isDiscounted ? $discountedSale : $retailSale;
                $totalNonDiscountedCredit += $isDiscounted ? 0 : $retailSale;
            }

            // ── Step 6: Update Sale totals for every pump ────────────────
            // Discounts and credits were all attached to $firstSale, so only
            // that sale gets non-zero discount/credit figures. All other pump
            // sales only need their gross → net → cash recomputed (which is
            // gross → gross since they carry no discount/credit rows).
            // This replaces the DB trigger entirely — no trigger needed.
            foreach ($saleByPump as $pumpId => $sale) {
                $isFirst = $sale->SalesID === ($firstSale->SalesID ?? null);

                $disc  = $isFirst ? $totalDiscountAmount      : 0;
                $cred  = $isFirst ? $totalCreditAmount        : 0;
                $nonDC = $isFirst ? $totalNonDiscountedCredit : 0;

                $gross = $sale->computed_gross_sales;
                $net   = $gross - $disc;
                $cash  = $net   - $nonDC;

                $sale->update([
                    'total_discount'        => $disc,
                    'total_credit'          => $cred,
                    'computed_net_sales'    => $net,
                    'computed_cash_in_hand' => $cash,
                ]);
            }

            // ── Step 7: Close the shift ───────────────────────────────────
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
        $shift->load([
            'shiftReadings.pump',
            'shiftReadings.fuel',
            'sales.salesDiscounts',
            'sales.salesCredits.credit',
        ]);
        $pumps     = Pump::with(['pumpFuels.fuel'])->orderBy('pump_name')->get();
        $fuels     = Fuel::orderBy('fuel_name')->get();
        $customers = Customer::orderBy('First_name')->get();
        return view('Shift.edit', compact('shift', 'pumps', 'fuels', 'customers'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'readings'                   => 'nullable|array',
            'readings.*.opening_reading' => 'nullable|numeric|min:0',
            'readings.*.closing_reading' => 'nullable|numeric|min:0',
            'readings.*.price_per_liter' => 'nullable|numeric|min:0',
            'existing_discounts'         => 'nullable|array',
            'existing_credits'           => 'nullable|array',
            'new_discounts'              => 'nullable|array',
            'new_credits'                => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $shift) {

            // 1. Update shift readings
            foreach ($request->input('readings', []) as $readingId => $data) {
                $reading = ShiftReading::find($readingId);
                if (!$reading || $reading->ShiftID !== $shift->ShiftID) continue;
                $reading->update([
                    'opening_reading' => (float)($data['opening_reading'] ?? $reading->opening_reading),
                    'closing_reading' => isset($data['closing_reading']) ? (float)$data['closing_reading'] : $reading->closing_reading,
                    'price_per_liter' => isset($data['price_per_liter'])  ? (float)$data['price_per_liter']  : $reading->price_per_liter,
                ]);
            }

            $shift->load('shiftReadings');

            // 2. Recalculate sales gross per pump
            foreach ($shift->shiftReadings->groupBy('PumpID') as $pumpId => $readings) {
                $sale = Sale::where('ShiftID', $shift->ShiftID)->where('PumpID', $pumpId)->first();
                if (!$sale) continue;
                $pumpLiters = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading));
                $pumpGross  = $readings->sum(fn($r) => max(0, ($r->closing_reading ?? 0) - $r->opening_reading) * ($r->price_per_liter ?? 0));
                $sale->update(['totalizer_liters' => $pumpLiters, 'computed_gross_sales' => $pumpGross]);
                foreach ($readings as $sr) {
                    SalesDetail::where('SalesID', $sale->SalesID)->where('FuelID', $sr->FuelID)->update([
                        'Price_per_Liter' => $sr->price_per_liter ?? 0,
                        'Liters'          => max(0, ($sr->closing_reading ?? 0) - $sr->opening_reading),
                    ]);
                }
            }

            $firstSale = Sale::where('ShiftID', $shift->ShiftID)->first();

            // 3. Update existing discounts
            foreach ($request->input('existing_discounts', []) as $sdId => $data) {
                $sd = SalesDiscount::find($sdId);
                if (!$sd) continue;
                if (($data['_delete'] ?? '0') === '1') { $sd->delete(); continue; }
                $sd->update([
                    'FuelID'             => $data['fuel_id']          ?? $sd->FuelID,
                    'CustomerID'         => $data['customer_id']       ?? $sd->CustomerID,
                    'liters'             => (float)($data['liters']             ?? $sd->liters),
                    'retail_price'       => (float)($data['retail_price']       ?? $sd->retail_price),
                    'discount_per_liter' => (float)($data['discount_per_liter'] ?? $sd->discount_per_liter),
                    'discount_sale'      => (float)($data['discount_sale']       ?? $sd->discount_sale),
                    'description'        => $data['description'] ?? $sd->description,
                ]);
                if ($sd->discount) {
                    $sd->discount->update([
                        'CustomerID'     => $data['customer_id']       ?? $sd->CustomerID,
                        'discount_value' => (float)($data['discount_per_liter'] ?? $sd->discount_per_liter),
                        'description'    => $data['description'] ?? $sd->description,
                    ]);
                }
            }

            // 4. Update existing credits
            foreach ($request->input('existing_credits', []) as $scId => $data) {
                $sc = SalesCredit::find($scId);
                if (!$sc) continue;
                if (($data['_delete'] ?? '0') === '1') { $sc->delete(); continue; }
                $sc->update([
                    'CustomerID'      => $data['customer_id']   ?? $sc->CustomerID,
                    'liters'          => (float)($data['liters']          ?? $sc->liters),
                    'retail_price'    => (float)($data['retail_price']    ?? $sc->retail_price),
                    'retail_sale'     => (float)($data['retail_sale']     ?? $sc->retail_sale),
                    'discounted'      => !empty($data['discounted']),
                    'discounted_sale' => (float)($data['discounted_sale'] ?? 0),
                    'description'     => $data['description'] ?? $sc->description,
                ]);
                if ($sc->credit) {
                    $pumpFuelRecord = PumpFuel::where('FuelID', $data['fuel_id'] ?? $sc->credit->FuelID)->first();
                    $sc->credit->update([
                        'CustomerID'      => $data['customer_id'] ?? $sc->credit->CustomerID,
                        'FuelID'          => $data['fuel_id']     ?? $sc->credit->FuelID,
                        'PumpFuelID'      => $pumpFuelRecord?->PumpFuelID ?? $sc->credit->PumpFuelID,
                        'Quantity'        => (float)($data['liters']       ?? $sc->credit->Quantity),
                        'price_per_liter' => (float)($data['retail_price'] ?? $sc->credit->price_per_liter),
                    ]);
                }
            }

            // 5. Add new discounts
            foreach ($request->input('new_discounts', []) as $d) {
                if ((float)($d['discount_sale'] ?? 0) <= 0 || !$firstSale) continue;
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
                    'FuelID'             => $d['fuel_id']          ?? null,
                    'CustomerID'         => $d['customer_id']       ?? null,
                    'liters'             => (float)($d['liters']             ?? 0),
                    'retail_price'       => (float)($d['retail_price']       ?? 0),
                    'discount_per_liter' => (float)($d['discount_per_liter'] ?? 0),
                    'discount_sale'      => (float)($d['discount_sale']       ?? 0),
                    'description'        => $d['description'] ?? null,
                ]);
            }

            // 6. Add new credits
            foreach ($request->input('new_credits', []) as $c) {
                $liters     = (float)($c['liters']      ?? 0);
                $customerId = $c['customer_id'] ?? null;
                $fuelId     = $c['fuel_id']     ?? null;
                if (!$customerId || !$fuelId || $liters <= 0 || !$firstSale) continue;
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
                    'retail_price'    => $retailPrice,
                    'retail_sale'     => (float)($c['retail_sale']     ?? 0),
                    'discounted'      => !empty($c['discounted']),
                    'discounted_sale' => (float)($c['discounted_sale'] ?? 0),
                    'description'     => $c['description'] ?? null,
                ]);
            }

            // 7. Recalculate sale totals for ALL sales in this shift
            // (replaces Triggers 1 & 2 for the update/edit path)
            foreach (Sale::where('ShiftID', $shift->ShiftID)->with(['salesDiscounts', 'salesCredits'])->get() as $sale) {
                $totalDiscount = $sale->salesDiscounts->sum('discount_sale');

                // Non-discounted credits reduce cash in hand
                $nonDiscountedCredit = $sale->salesCredits
                    ->where('discounted', false)
                    ->sum('retail_sale');

                // Total credit column: discounted credits use discounted_sale, others use retail_sale
                $totalCredit = $sale->salesCredits->sum(
                    fn($c) => $c->discounted ? $c->discounted_sale : $c->retail_sale
                );

                $gross = $sale->computed_gross_sales;
                $net   = $gross - $totalDiscount;
                $cash  = $net   - $nonDiscountedCredit;

                $sale->update([
                    'total_discount'        => $totalDiscount,
                    'total_credit'          => $totalCredit,
                    'computed_net_sales'    => $net,
                    'computed_cash_in_hand' => $cash,
                ]);
            }
        });

        return redirect()->route('shift.management', ['view' => 'home'])
            ->with('success', 'Shift updated successfully.');
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