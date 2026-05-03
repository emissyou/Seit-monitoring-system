<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditPayment;
use App\Models\Customer;
use App\Models\Fuel;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        $query = Credit::with('customer', 'fuel')->latest('CreditID');

        if ($request->filled('customer_id')) {
            $query->where('CustomerID', $request->customer_id);
        }
        if ($request->filled('date_from')) {
            $query->where('credit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('credit_date', '<=', $request->date_to);
        }

        $credits   = $query->paginate(20);
        $customers = Customer::orderBy('First_name')->get();

        return view('credits.index', compact('credits', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,CustomerID',
            'fuel_id'     => 'required|exists:fuels,FuelID',
            'credit_date' => 'required|date',
            'Quantity'    => 'required|numeric|min:0',
        ]);

        Credit::create([
            'CustomerID'  => $validated['customer_id'],
            'FuelID'      => $validated['fuel_id'],
            'Quantity'    => $validated['Quantity'],
            'credit_date' => $validated['credit_date'],
        ]);

        return redirect()->route('customers', ['open_customer' => $validated['customer_id']])
                         ->with('success', 'Credit added successfully.');
    }

    /**
     * Return a customer's credits as JSON (used by modal).
     */
    public function byCustomer($id)
    {
        $credits = Credit::with('fuel')
            ->where('CustomerID', $id)
            ->latest('CreditID')
            ->get();

        return response()->json($credits);
    }

    /**
     * Show detail of a single credit (JSON for modal).
     */
    public function detail($id)
    {
        $credit = Credit::with('customer', 'fuel', 'salesCredits')->findOrFail($id);
        return response()->json($credit);
    }

    /**
     * Record a payment against a credit.
     */
    public function pay(Request $request, $id)
    {
        $credit = Credit::findOrFail($id);

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount_paid'  => 'required|numeric|min:0.01',
            'note'         => 'nullable|string|max:255',
        ]);

        CreditPayment::create([
            'CreditID'     => $credit->CreditID,
            'CustomerID'   => $credit->CustomerID,
            'payment_date' => $validated['payment_date'],
            'amount_paid'  => $validated['amount_paid'],
            'note'         => $validated['note'],
        ]);

        return redirect()->route('customers', ['open_customer' => $credit->CustomerID])
                         ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Update the status of a credit (e.g. paid / partial / unpaid).
     */
    public function updateStatus(Request $request, $id)
    {
        $credit = Credit::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:unpaid,partial,paid',
        ]);

        $credit->update(['status' => $validated['status']]);

        return back()->with('success', 'Credit status updated.');
    }

    /**
     * Archive (soft-hide) a credit.
     */
    public function archive($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->update(['archived' => true]);

        return back()->with('success', 'Credit archived.');
    }

    /**
     * Permanently delete a credit.
     */
    public function destroy($id)
    {
        Credit::findOrFail($id)->delete();

        return back()->with('success', 'Credit deleted.');
    }
}