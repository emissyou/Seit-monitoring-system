<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PumpFuel;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $balance = $request->input('balance'); // 'with' | 'without' | null

        // Active customers — eager-load credits with their fuel price and payments
        // so the blade can compute the outstanding balance without extra queries.
        $query = Customer::where('is_active', true)
            ->with(['credits' => function ($q) {
                $q->where('archived', false)->with(['fuel', 'payments']);
            }])
            ->latest('CustomerID');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('First_name', 'like', "%{$search}%")
                  ->orWhere('Middle_name', 'like', "%{$search}%")
                  ->orWhere('Last_name', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(First_name, ' ', Last_name) like ?", ["%{$search}%"])
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by whether the customer has any (non-archived) credit records
        if ($balance === 'with') {
            $query->whereHas('credits', fn($q) => $q->where('archived', false));
        } elseif ($balance === 'without') {
            $query->whereDoesntHave('credits', fn($q) => $q->where('archived', false));
        }

        $customers = $query->paginate(6)->withQueryString();

        // Archived customers (no credit loading needed here)
        $archivedCustomers = Customer::where('is_active', false)
            ->latest('CustomerID')
            ->paginate(6, ['*'], 'archived_page')
            ->withQueryString();

        // Load pump fuels with pump name + fuel name + price for the Add Credit dropdown
        $fuels = PumpFuel::with(['pump', 'fuel'])
            ->orderBy('PumpID')
            ->get();

        return view('customers.index', compact('customers', 'archivedCustomers', 'search', 'balance', 'fuels'));
    }

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
            'First_name'     => $request->fname,
            'Middle_name'    => $request->mname,
            'Last_name'      => $request->lname,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'is_active'      => true,
        ]);

        return redirect()->route('customers')
                         ->with('success', 'Customer added successfully!');
    }

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
            'First_name'     => $request->fname,
            'Middle_name'    => $request->mname,
            'Last_name'      => $request->lname,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
        ]);

        return redirect()->route('customers')
                         ->with('success', 'Customer updated successfully!');
    }

    public function archive($id)
    {
        $customer  = Customer::findOrFail($id);
        $newStatus = ! $customer->is_active;

        $customer->update(['is_active' => $newStatus]);

        $message = $newStatus ? 'Customer restored successfully!' : 'Customer archived successfully!';

        return redirect()->route('customers')->with('success', $message);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->is_active) {
            return redirect()->route('customers')
                ->with('error', 'Only archived customers can be permanently deleted.');
        }

        $customer->delete();

        return redirect()->route('customers')
            ->with('success', 'Customer permanently deleted.');
    }
}