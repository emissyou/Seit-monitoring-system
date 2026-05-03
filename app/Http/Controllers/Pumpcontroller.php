<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\Pump;
use App\Models\PumpFuel;
use Illuminate\Http\Request;

class PumpController extends Controller
{
    public function index()
    {
        $pumps = Pump::with('pumpFuels.fuel')->get();
        $fuels = Fuel::orderBy('fuel_name')->get();
        return view('Pumps.index', compact('pumps', 'fuels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pump_name' => 'required|string|max:255|unique:pumps,pump_name',
            'fuel_ids'  => 'nullable|array',
            'fuel_ids.*'=> 'exists:fuels,FuelID',
        ]);

        $pump = Pump::create(['pump_name' => $request->pump_name]);

        if ($request->filled('fuel_ids')) {
            foreach ($request->fuel_ids as $fuelId) {
                PumpFuel::create([
                    'PumpID'            => $pump->id,
                    'FuelID'            => $fuelId,
                    'totalizer_reading' => 0,
                ]);
            }
        }

        return back()->with('success', "Pump \"{$pump->pump_name}\" added successfully.");
    }

    public function update(Request $request, $id)
    {
        $pump = Pump::findOrFail($id);

        $request->validate([
            'pump_name' => 'required|string|max:255|unique:pumps,pump_name,' . $pump->id,
            'fuel_ids'  => 'nullable|array',
            'fuel_ids.*'=> 'exists:fuels,FuelID',
        ]);

        $pump->update(['pump_name' => $request->pump_name]);

        // Sync fuel assignments
        PumpFuel::where('PumpID', $pump->id)->delete();

        if ($request->filled('fuel_ids')) {
            foreach ($request->fuel_ids as $fuelId) {
                PumpFuel::create([
                    'PumpID'            => $pump->id,
                    'FuelID'            => $fuelId,
                    'totalizer_reading' => 0,
                ]);
            }
        }

        return back()->with('success', "Pump \"{$pump->pump_name}\" updated successfully.");
    }

    public function destroy($id)
    {
        $pump = Pump::findOrFail($id);
        $name = $pump->pump_name;
        PumpFuel::where('PumpID', $pump->id)->delete();
        $pump->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "Pump \"{$name}\" deleted.");
    }
}