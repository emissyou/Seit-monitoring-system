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
            ->latest()
            ->paginate(15);

        $customers = Customer::orderBy('first_name')->get();

        return view('discounts.index', compact('discounts', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'discount_type'  => 'required|in:per_liter,fixed_amount,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'description'    => 'nullable|string|max:255',
        ]);

        Discount::create($validated);

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