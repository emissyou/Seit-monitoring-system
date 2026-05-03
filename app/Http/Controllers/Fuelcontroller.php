<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    public function index()
    {
        $fuels = Fuel::orderBy('fuel_name')->get();
        return view('Fuels.index', compact('fuels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fuel_name'   => 'required|string|max:255|unique:fuels,fuel_name',
            'octane'      => 'nullable|numeric|min:0|max:999',
            'description' => 'nullable|string|max:500',
        ]);

        Fuel::create([
            'fuel_name'   => $request->fuel_name,
            'octane'      => $request->octane,
            'description' => $request->description,
        ]);

        return back()->with('success', "Fuel \"{$request->fuel_name}\" added successfully.");
    }

    public function update(Request $request, $id)
    {
        $fuel = Fuel::findOrFail($id);

        $request->validate([
            'fuel_name'   => 'required|string|max:255|unique:fuels,fuel_name,' . $fuel->FuelID . ',FuelID',
            'octane'      => 'nullable|numeric|min:0|max:999',
            'description' => 'nullable|string|max:500',
        ]);

        $fuel->update([
            'fuel_name'   => $request->fuel_name,
            'octane'      => $request->octane,
            'description' => $request->description,
        ]);

        return back()->with('success', "Fuel \"{$fuel->fuel_name}\" updated successfully.");
    }

    public function destroy($id)
    {
        $fuel = Fuel::findOrFail($id);
        $name = $fuel->fuel_name;
        $fuel->delete();

        return back()->with('success', "Fuel \"{$name}\" deleted.");
    }
}