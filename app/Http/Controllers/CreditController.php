<?php

namespace App\Http\Controllers;

use App\Models\Credit;        // points to `credits` table  — used for WRITES
use App\Models\CreditView;    // points to `credit_summary_view` — used for READS
use App\Models\CreditPayment;
use App\Models\Customer;
use App\Models\PumpFuel;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    // ── READ: index ────────────────────────────────────────────────────────
    // View already contains: customer_name, fuel_name, total_amount,
    //                        amount_paid, remaining_balance
    public function index(Request $request)
    {
        $query = CreditView::latest('CreditID');

        if ($request->filled('customer_id')) {
            $query->where('CustomerID', $request->customer_id);
        }

        $credits   = $query->paginate(20);
        $customers = Customer::orderBy('First_name')->get();

        return view('credits.index', compact('credits', 'customers'));
    }

    // ── READ: export CSV ───────────────────────────────────────────────────
    public function export(Request $request)
    {
        $query = CreditView::latest('CreditID');

        if ($request->filled('customer_id')) {
            $query->where('CustomerID', $request->customer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('credit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('credit_date', '<=', $request->date_to);
        }

        $credits = $query->get();

        $header = [
            'Date',
            'Customer',
            'Fuel Type',
            'Quantity (L)',
            'Price/Liter',
            'Discount Amount',
            'Total Amount',
            'Amount Paid',
            'Remaining Balance',
            'Status',
        ];

        $rows = $credits->map(fn($c) => [
            optional($c->credit_date)->format('Y-m-d'),
            $c->customer_name,                          // from view
            $c->fuel_name,                              // from view
            number_format((float) $c->Quantity, 3),
            number_format((float) $c->price_per_liter, 2),
            number_format((float) $c->discount_amount, 2),
            number_format((float) $c->total_amount, 2), // from view
            number_format((float) $c->amount_paid, 2),  // from view
            number_format((float) $c->remaining_balance, 2), // from view
            ucfirst($c->status ?? 'unpaid'),
        ]);

        return $this->streamCsv('credit_logs', $header, $rows);
    }

    // ── WRITE: store ───────────────────────────────────────────────────────
    // Views are read-only — write directly to the `credits` table via Credit model
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'        => 'required|exists:customers,CustomerID',
            'pump_fuel_id'       => 'required|exists:pump_fuels,PumpFuelID',
            'credit_date'        => 'required|date',
            'Quantity'           => 'required|numeric|min:0.001',
            'price_per_liter'    => 'required|numeric|min:0',
            'discount_per_liter' => 'nullable|numeric|min:0',
        ]);

        $pumpFuel         = PumpFuel::findOrFail($validated['pump_fuel_id']);
        $quantity         = (float) $validated['Quantity'];
        $discountPerLiter = (float) ($validated['discount_per_liter'] ?? 0);

        Credit::create([
            'CustomerID'      => $validated['customer_id'],
            'FuelID'          => $pumpFuel->FuelID,
            'PumpFuelID'      => $pumpFuel->PumpFuelID,
            'Quantity'        => $quantity,
            'price_per_liter' => (float) $validated['price_per_liter'],
            'discount_amount' => round($discountPerLiter * $quantity, 2),
            'credit_date'     => $validated['credit_date'],
            'status'          => 'unpaid',
            'archived'        => false,
        ]);

        return redirect()->route('customers', ['open_customer' => $validated['customer_id']])
                         ->with('success', 'Credit added successfully.');
    }

    // ── READ: credits by customer (JSON) ───────────────────────────────────
    public function byCustomer($id)
    {
        $credits = CreditView::where('CustomerID', $id)
            ->where('archived', false)
            ->latest('CreditID')
            ->get();

        $data = $credits->map(fn($c) => [
            'id'                => $c->CreditID,
            'date'              => $c->credit_date,
            'fuel_type'         => $c->fuel_name,                   // from view
            'liters'            => (float) $c->Quantity,
            'price'             => (float) $c->price_per_liter,
            'discount'          => (float) $c->discount_amount,
            'amount'            => (float) $c->total_amount,        // from view
            'amount_paid'       => (float) $c->amount_paid,         // from view
            'remaining_balance' => (float) $c->remaining_balance,   // from view
            'payment_status'    => $c->status ?? 'unpaid',
        ]);

        return response()->json($data);
    }

    // ── READ: single credit detail (JSON) ──────────────────────────────────
    public function detail($id)
    {
        $credit = CreditView::findOrFail($id);

        // Payments are not in the view — fetch them from the payments table
        $payments = CreditPayment::where('CreditID', $id)
            ->get()
            ->map(fn($p) => [
                'payment_date' => $p->payment_date,
                'amount_paid'  => (float) $p->amount_paid,
                'note'         => $p->note,
            ]);

        return response()->json([
            'id'                => $credit->CreditID,
            'date'              => $credit->credit_date,
            'fuel_type'         => $credit->fuel_name,                  // from view
            'liters'            => (float) $credit->Quantity,
            'price'             => (float) $credit->price_per_liter,
            'discount'          => (float) $credit->discount_amount,
            'subtotal'          => (float) $credit->total_amount,       // from view (= subtotal after discount)
            'amount'            => (float) $credit->total_amount,       // from view
            'amount_paid'       => (float) $credit->amount_paid,        // from view
            'remaining_balance' => (float) $credit->remaining_balance,  // from view
            'payment_status'    => $credit->status ?? 'unpaid',
            'payments'          => $payments,
        ]);
    }

    // ── WRITE: record a payment ────────────────────────────────────────────
    public function pay(Request $request, $id)
    {
        // Use the view only to get computed totals
        $creditView = CreditView::findOrFail($id);

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount_paid'  => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:255',
        ]);

        CreditPayment::create([
            'CreditID'     => $id,
            'payment_date' => $validated['payment_date'],
            'amount_paid'  => $validated['amount_paid'],
            'note'         => $validated['note'],
        ]);

        // Re-query the view after inserting so amount_paid is up to date
        $updated    = CreditView::findOrFail($id);
        $totalPaid  = (float) $updated->amount_paid;
        $totalAmount = (float) $updated->total_amount;

        if ($totalAmount > 0) {
            $status = $totalPaid >= $totalAmount ? 'paid'
                    : ($totalPaid > 0            ? 'partial' : 'unpaid');

            // Write status back to the base `credits` table
            Credit::where('CreditID', $id)->update(['status' => $status]);
        }

        return redirect()->route('customers', ['open_customer' => $creditView->CustomerID])
                         ->with('success', 'Payment recorded successfully.');
    }

    // ── WRITE: manually override status ───────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate(['status' => 'required|in:unpaid,partial,paid']);

        // Read CustomerID from view, write status to base table
        $creditView = CreditView::findOrFail($id);
        Credit::where('CreditID', $id)->update(['status' => $validated['status']]);

        return redirect()->route('customers', ['open_customer' => $creditView->CustomerID])
                         ->with('success', 'Credit status updated.');
    }

    // ── WRITE: archive ─────────────────────────────────────────────────────
    public function archive($id)
    {
        $creditView = CreditView::findOrFail($id);
        Credit::where('CreditID', $id)->update(['archived' => true]);

        return redirect()->route('customers', ['open_customer' => $creditView->CustomerID])
                         ->with('success', 'Credit archived.');
    }

    // ── WRITE: delete ──────────────────────────────────────────────────────
    public function destroy($id)
    {
        $creditView = CreditView::findOrFail($id);
        $customerId = $creditView->CustomerID;

        Credit::where('CreditID', $id)->delete();

        return redirect()->route('customers', ['open_customer' => $customerId])
                         ->with('success', 'Credit permanently deleted.');
    }

    // ── Shared CSV streamer ────────────────────────────────────────────────

    private function streamCsv(string $filename, array $header, $rows)
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}_" . now()->format('Ymd_His') . ".csv\"",
        ];

        return response()->stream(function () use ($header, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $header);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, $headers);
    }
}