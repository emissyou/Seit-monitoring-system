<?php

namespace App\Http\Controllers;

use App\Models\Pump;
use App\Models\PumpFuel;
use App\Models\Fuel;
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
            'pump_name'    => 'required|string|max:255|unique:pumps,pump_name',
            'fuel_ids'     => 'nullable|array',
            'fuel_ids.*'   => 'exists:fuels,FuelID',
            'prices'       => 'nullable|array',
            'prices.*'     => 'nullable|numeric|min:0',
        ]);

        $pump = Pump::create(['pump_name' => $request->pump_name]);

        if ($request->filled('fuel_ids')) {
            foreach ($request->fuel_ids as $fuelId) {
                PumpFuel::create([
                    'PumpID'            => $pump->pumpID,
                    'FuelID'            => $fuelId,
                    'totalizer_reading' => 0,
                    'price_per_liter'   => (float) ($request->prices[$fuelId] ?? 0),
                ]);
            }
        }

        return back()->with('success', "Pump \"{$pump->pump_name}\" added successfully.");
    }

    public function update(Request $request, $id)
    {
        $pump = Pump::findOrFail($id);

        $request->validate([
            'pump_name'    => 'required|string|max:255|unique:pumps,pump_name,' . $pump->pumpID . ',pumpID',
            'fuel_ids'     => 'nullable|array',
            'fuel_ids.*'   => 'exists:fuels,FuelID',
            'prices'       => 'nullable|array',
            'prices.*'     => 'nullable|numeric|min:0',
        ]);

        $pump->update(['pump_name' => $request->pump_name]);

        // Load existing pump-fuel assignments keyed by FuelID
        $existingByFuel = PumpFuel::where('PumpID', $pump->pumpID)
            ->get()
            ->keyBy('FuelID');

        $newFuelIds = $request->fuel_ids ?? [];

        // Delete removed fuels
        foreach ($existingByFuel as $fuelId => $pf) {
            if (!in_array($fuelId, $newFuelIds)) {
                $pf->delete();
            }
        }

        // Create new or update existing fuel assignments with price
        foreach ($newFuelIds as $fuelId) {
            $price = (float) ($request->prices[$fuelId] ?? 0);

            if (isset($existingByFuel[$fuelId])) {
                // Update price only — preserve totalizer_reading
                $existingByFuel[$fuelId]->update(['price_per_liter' => $price]);
            } else {
                PumpFuel::create([
                    'PumpID'            => $pump->pumpID,
                    'FuelID'            => $fuelId,
                    'totalizer_reading' => 0,
                    'price_per_liter'   => $price,
                ]);
            }
        }

        return back()->with('success', "Pump \"{$pump->pump_name}\" updated successfully.");
    }

    public function destroy($id)
    {
        $pump = Pump::findOrFail($id);
        $name = $pump->pump_name;

        PumpFuel::where('PumpID', $pump->pumpID)->delete();
        $pump->delete();

        return back()->with('success', "Pump \"{$name}\" deleted.");
    }
}