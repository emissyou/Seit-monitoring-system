<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditPayment;
use App\Models\Customer;
use App\Models\PumpFuel;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display credits list
     */
    public function index(Request $request)
    {
        $query = Credit::with('customer', 'fuel')->latest('CreditID');

        if ($request->filled('customer_id')) {
            $query->where('CustomerID', $request->customer_id);
        }

        $credits   = $query->paginate(20);
        $customers = Customer::orderBy('First_name')->get();

        return view('credits.index', compact('credits', 'customers'));
    }

    /**
     * Store new Credit
     * Receives pump_fuel_id from the blade, looks up FuelID + price_per_liter from PumpFuel.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'      => 'required|exists:customers,CustomerID',
            'pump_fuel_id'     => 'required|exists:pump_fuels,PumpFuelID',
            'credit_date'      => 'required|date',
            'Quantity'         => 'required|numeric|min:0.001',
            'price_per_liter'  => 'required|numeric|min:0',
            'discount_per_liter' => 'nullable|numeric|min:0',
        ]);

        // Look up the PumpFuel to get FuelID
        $pumpFuel = PumpFuel::findOrFail($validated['pump_fuel_id']);

        $quantity        = (float) $validated['Quantity'];
        $pricePerLiter   = (float) $validated['price_per_liter'];
        $discountPerLiter = (float) ($validated['discount_per_liter'] ?? 0);
        // Store total discount amount (discount per liter × liters)
        $discountAmount  = round($discountPerLiter * $quantity, 2);

        Credit::create([
            'CustomerID'      => $validated['customer_id'],
            'FuelID'          => $pumpFuel->FuelID,
            'PumpFuelID'      => $pumpFuel->PumpFuelID,
            'Quantity'        => $quantity,
            'price_per_liter' => $pricePerLiter,
            'discount_amount' => $discountAmount,
            'credit_date'     => $validated['credit_date'],
            'status'          => 'unpaid',
            'archived'        => false,
        ]);

        return redirect()->route('customers', ['open_customer' => $validated['customer_id']])
                         ->with('success', 'Credit added successfully.');
    }

    /**
     * Get customer's credits for modal (JSON)
     */
    public function byCustomer($id)
    {
        $credits = Credit::with(['fuel', 'pumpFuel', 'payments'])
            ->where('CustomerID', $id)
            ->where('archived', false)
            ->latest('CreditID')
            ->get();

        $data = $credits->map(function ($c) {
            // Use stored price_per_liter — falls back to pumpFuel or fuel price if column not yet migrated
            $price      = (float) ($c->price_per_liter
                          ?? optional($c->pumpFuel)->price_per_liter
                          ?? 0);
            $quantity   = (float) $c->Quantity;
            $subtotal   = round($price * $quantity, 2);
            $discount   = (float) ($c->discount_amount ?? 0);
            $amount     = max(0, $subtotal - $discount);
            $amountPaid = (float) $c->payments->sum('amount_paid');
            $remaining  = max(0, $amount - $amountPaid);

            return [
                'id'                => $c->CreditID,
                'date'              => $c->credit_date,
                'fuel_type'         => optional($c->fuel)->fuel_name ?? '—',
                'liters'            => $quantity,
                'price'             => $price,
                'discount'          => $discount,
                'amount'            => $amount,
                'amount_paid'       => $amountPaid,
                'remaining_balance' => $remaining,
                'payment_status'    => $c->status ?? 'unpaid',
            ];
        });

        return response()->json($data);
    }

    /**
     * Get single credit detail for modal (JSON)
     */
    public function detail($id)
    {
        $credit = Credit::with(['fuel', 'pumpFuel', 'payments'])->findOrFail($id);

        $price      = (float) ($credit->price_per_liter
                      ?? optional($credit->pumpFuel)->price_per_liter
                      ?? 0);
        $quantity   = (float) $credit->Quantity;
        $subtotal   = round($price * $quantity, 2);
        $discount   = (float) ($credit->discount_amount ?? 0);
        $amount     = max(0, $subtotal - $discount);
        $amountPaid = (float) $credit->payments->sum('amount_paid');
        $remaining  = max(0, $amount - $amountPaid);

        return response()->json([
            'id'                => $credit->CreditID,
            'date'              => $credit->credit_date,
            'fuel_type'         => optional($credit->fuel)->fuel_name ?? '—',
            'liters'            => $quantity,
            'price'             => $price,
            'discount'          => $discount,
            'subtotal'          => $subtotal,
            'amount'            => $amount,
            'amount_paid'       => $amountPaid,
            'remaining_balance' => $remaining,
            'payment_status'    => $credit->status ?? 'unpaid',
            'payments'          => $credit->payments->map(fn($p) => [
                'payment_date' => $p->payment_date,
                'amount_paid'  => (float) $p->amount_paid,
                'note'         => $p->note,
            ]),
        ]);
    }

    /**
     * Record Payment
     */
    public function pay(Request $request, $id)
    {
        $credit = Credit::with('payments')->findOrFail($id);

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount_paid'  => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:255',
        ]);

        CreditPayment::create([
            'CreditID'     => $credit->CreditID,
            'payment_date' => $validated['payment_date'],
            'amount_paid'  => $validated['amount_paid'],
            'note'         => $validated['note'],
        ]);

        // Recalculate status using stored price
        $price       = (float) ($credit->price_per_liter ?? 0);
        $quantity    = (float) $credit->Quantity;
        $subtotal    = round($price * $quantity, 2);
        $discount    = (float) ($credit->discount_amount ?? 0);
        $totalAmount = max(0, $subtotal - $discount);
        $totalPaid   = (float) $credit->payments()->sum('amount_paid') + (float) $validated['amount_paid'];

        if ($totalAmount > 0) {
            $status = $totalPaid >= $totalAmount ? 'paid'
                    : ($totalPaid > 0 ? 'partial' : 'unpaid');
            $credit->update(['status' => $status]);
        }

        return redirect()->route('customers', ['open_customer' => $credit->CustomerID])
                         ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Update Credit Status
     */
    public function updateStatus(Request $request, $id)
    {
        $credit = Credit::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:unpaid,partial,paid',
        ]);

        $credit->update(['status' => $validated['status']]);

        return redirect()->route('customers', ['open_customer' => $credit->CustomerID])
                         ->with('success', 'Credit status updated.');
    }

    /**
     * Archive Credit
     */
    public function archive($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->update(['archived' => true]);

        return redirect()->route('customers', ['open_customer' => $credit->CustomerID])
                         ->with('success', 'Credit archived.');
    }

    /**
     * Permanently Delete Credit
     */
    public function destroy($id)
    {
        $credit     = Credit::findOrFail($id);
        $customerId = $credit->CustomerID;
        $credit->delete();

        return redirect()->route('customers', ['open_customer' => $customerId])
                         ->with('success', 'Credit permanently deleted.');
    }
}