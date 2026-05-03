<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Customer;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::with('customer')
            ->latest('DiscountID')
            ->paginate(15);

        $customers = Customer::orderBy('First_name')->get();

        return view('discounts.index', compact('discounts', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'    => 'required|exists:customers,CustomerID',
            'discount_type'  => 'required|in:per_liter,fixed_amount,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'description'    => 'nullable|string|max:255',
        ]);

        Discount::create([
            'CustomerID'     => $validated['customer_id'],
            'discount_type'  => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date'     => $validated['start_date'],
            'end_date'       => $validated['end_date'],
            'description'    => $validated['description'],
        ]);

        return redirect()->route('discounts.index')
                         ->with('success', 'Discount created successfully.');
    }

    public function archive($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->update(['archived' => true]);

        return redirect()->route('discounts.index')
                         ->with('success', 'Discount archived successfully.');
    }

    public function destroy($id)
    {
        Discount::findOrFail($id)->delete();

        return redirect()->route('discounts.index')
                         ->with('success', 'Discount deleted successfully.');
    }
}