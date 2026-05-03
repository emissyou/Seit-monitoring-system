<?php
// FILE: app/Http/Controllers/CreditController.php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CreditPayment;
use App\Models\Customer;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        $query = Credit::with('customer')->latest();

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $credits = $query->paginate(20);

        $customers = Customer::orderBy('first_name')->get();

        return view('credits.index', compact('credits', 'customers'));
    }

        /**
     * PATCH /credits/{id}/archive
     * Archive a credit record (soft delete style)
     */
    public function archive($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->update(['archived' => true]);

        return redirect()
            ->route('credits.index')
            ->with('success', 'Credit archived successfully.');
    }

    /**
     * GET /customers/{id}/credits
     * Returns credit list as JSON for the View modal table.
     */
    public function byCustomer($id)
    {
        Customer::findOrFail($id); // 404 if customer missing

        $credits = Credit::where('customer_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($c) => [
                'id'                => $c->id,
                'date'              => optional($c->date)->toDateString() ?? '',
                'fuel_type'         => $c->fuel_type ?? '',
                'price'             => (float) ($c->price ?? 0),
                'liters'            => (float) ($c->liters ?? 0),
                'amount'            => (float) ($c->amount ?? 0),
                'amount_paid'       => (float) ($c->amount_paid ?? 0),
                'balance'           => (float) ($c->balance ?? 0),
                'remaining_balance' => $c->remaining_balance,
                'payment_status'    => $c->payment_status ?? 'unpaid',
            ]);

        return response()->json($credits);
    }

    /**
     * GET /credits/{id}/detail
     * Returns full credit detail + payment history as JSON for the detail modal.
     */
    public function detail($id)
    {
        $credit = Credit::with('payments')->findOrFail($id);

        return response()->json([
            'id'                => $credit->id,
            'date'              => optional($credit->date)->toDateString() ?? '',
            'fuel_type'         => $credit->fuel_type ?? '',
            'price'             => (float) ($credit->price ?? 0),
            'liters'            => (float) ($credit->liters ?? 0),
            'amount'            => (float) ($credit->amount ?? 0),
            'amount_paid'       => (float) ($credit->amount_paid ?? 0),
            'remaining_balance' => $credit->remaining_balance,
            'payment_status'    => $credit->payment_status ?? 'unpaid',
            'payments'          => $credit->payments->map(fn($p) => [
                'id'           => $p->id,
                'payment_date' => optional($p->payment_date)->toDateString() ?? '',
                'amount_paid'  => (float) ($p->amount_paid ?? 0),
                'note'         => $p->note ?? '',
            ]),
        ]);
    }

    /**
     * POST /credits
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'date'        => ['required', 'date'],
            'fuel_type'   => ['required', 'string', 'max:50'],
            'price'       => ['required', 'numeric', 'min:0'],
            'liters'      => ['required', 'numeric', 'min:0'],
            'amount'      => ['required', 'numeric', 'min:0'],
        ]);

        $validated['amount'] = round($validated['price'] * $validated['liters'], 2);

        $previousBalance = Credit::where('customer_id', $validated['customer_id'])
            ->selectRaw('SUM(amount - amount_paid) as total')
            ->value('total') ?? 0;

        $validated['balance']        = round((float) $previousBalance + $validated['amount'], 2);
        $validated['payment_status'] = 'unpaid';
        $validated['amount_paid']    = 0;

        Credit::create($validated);

        return redirect()
            ->route('customers', ['open_customer' => $validated['customer_id']])
            ->with('success', 'Credit added successfully.');
    }

    /**
     * POST /credits/{id}/pay
     */
    public function pay(Request $request, $id)
    {
        $credit = Credit::findOrFail($id);

        $validated = $request->validate([
            'payment_date' => ['required', 'date'],
            'amount_paid'  => ['required', 'numeric', 'min:0.01',
                               'max:' . $credit->remaining_balance],
            'note'         => ['nullable', 'string', 'max:255'],
        ]);

        CreditPayment::create([
            'credit_id'    => $credit->id,
            'customer_id'  => $credit->customer_id,
            'payment_date' => $validated['payment_date'],
            'amount_paid'  => $validated['amount_paid'],
            'note'         => $validated['note'] ?? null,
        ]);

        $newAmountPaid = round((float) $credit->amount_paid + (float) $validated['amount_paid'], 2);
        $remaining     = round((float) $credit->amount - $newAmountPaid, 2);

        $status = $remaining <= 0 ? 'paid' : ($newAmountPaid > 0 ? 'partial' : 'unpaid');
        $credit->update([
            'amount_paid'    => $newAmountPaid,
            'payment_status' => $status,
        ]);

        return redirect()
            ->route('customers', ['open_customer' => $credit->customer_id])
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * PATCH /credits/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $credit = Credit::findOrFail($id);

        $request->validate([
            'payment_status' => ['required', 'in:unpaid,partial,paid'],
        ]);

        $credit->update(['payment_status' => $request->payment_status]);

        return redirect()
            ->route('customers', ['open_customer' => $credit->customer_id])
            ->with('success', 'Credit status updated.');
    }
}