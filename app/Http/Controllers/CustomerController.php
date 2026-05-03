<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Show active customers (paginated 6/page).
     * Also loads archived customers for the archived table.
     */
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $balance = $request->input('balance'); // 'with' | 'without' | null

        // Active customers – shown in the main table
        $query = Customer::where('status', 'active')
            ->with('credits')
            ->latest();

        // Search by name, contact number, or address
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name',     'like', "%{$search}%")
                  ->orWhere('middle_name',  'like', "%{$search}%")
                  ->orWhere('last_name',    'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"])
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('address',      'like', "%{$search}%");
            });
        }

        // Filter by outstanding balance
        if ($balance === 'with') {
            $query->whereHas('credits', function ($q) {
                $q->whereRaw('(amount - amount_paid) > 0');
            });
        } elseif ($balance === 'without') {
            $query->whereDoesntHave('credits', function ($q) {
                $q->whereRaw('(amount - amount_paid) > 0');
            });
        }

        $customers = $query->paginate(6)->withQueryString();

        // Archived customers – shown in the archived panel
        $archivedQuery = Customer::where('status', 'archived')->latest();

        if ($search) {
            $archivedQuery->where(function ($q) use ($search) {
                $q->where('first_name',     'like', "%{$search}%")
                  ->orWhere('middle_name',  'like', "%{$search}%")
                  ->orWhere('last_name',    'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"])
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('address',      'like', "%{$search}%");
            });
        }

        $archivedCustomers = $archivedQuery
            ->paginate(6, ['*'], 'archived_page')
            ->withQueryString();

        return view('customers.index', compact('customers', 'archivedCustomers', 'search', 'balance'));
    }

    /**
     * Store a new customer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fname'          => 'required|string|max:255',
            'mname'          => 'nullable|string|max:255',
            'lname'          => 'required|string|max:255',
            'contact_number' => 'required|numeric|digits_between:10,11',
            'address'        => 'required|string|max:255',
        ]);

        Customer::create([
            'first_name'     => $request->fname,
            'middle_name'    => $request->mname,
            'last_name'      => $request->lname,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'status'         => 'active',
        ]);

        return redirect()->route('customers')->with('success', 'Customer added successfully!');
    }

    /**
     * Update an existing customer.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'fname'          => 'required|string|max:255',
            'mname'          => 'nullable|string|max:255',
            'lname'          => 'required|string|max:255',
            'contact_number' => 'required|numeric|digits_between:10,11',
            'address'        => 'required|string|max:255',
        ]);

        $customer->update([
            'first_name'     => $request->fname,
            'middle_name'    => $request->mname,
            'last_name'      => $request->lname,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
        ]);

        return redirect()->route('customers')->with('success', 'Customer updated successfully!');
    }

    /**
     * Toggle archive / restore (no permanent deletion from active table).
     */
    public function archive($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->status === 'archived') {
            $customer->update(['status' => 'active']);
            return redirect()->route('customers')->with('success', 'Customer restored successfully!');
        }

        $customer->update(['status' => 'archived']);
        return redirect()->route('customers')->with('success', 'Customer archived successfully!');
    }

    /**
     * Permanently delete a customer (only allowed from the archived list).
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        // Safety check: only archived customers can be permanently deleted
        if ($customer->status !== 'archived') {
            return redirect()->route('customers')->with('error', 'Only archived customers can be permanently deleted.');
        }

        $customer->delete();

        return redirect()->route('customers', ['tab' => 'archived'])
            ->with('success', 'Customer permanently deleted.');
    }
}