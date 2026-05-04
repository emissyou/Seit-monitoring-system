<?php

namespace App\Http\Controllers;

use App\Models\Pump;
use App\Models\Fuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TotalizerLog;

class TotalizerLogController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'PumpFuelID'    => 'required|integer',
            'reading'       => 'required|numeric|min:0',
            'date_recorded' => 'nullable|date',
        ]);

        TotalizerLog::create([
            'PumpFuelID'    => $request->PumpFuelID,
            'reading'       => $request->reading,
            'date_recorded' => $request->date_recorded ?? now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Totalizer log added successfully.');
    }

    public function index(Request $request)
    {
        $query = DB::table('totalizer_logs_view')
            ->orderBy('date_recorded', 'desc')
            ->orderBy('closed_at', 'desc');

        if ($request->filled('pump_id')) {
            $query->where('PumpID', (int) $request->pump_id);
        }
        if ($request->filled('fuel_id')) {
            $query->where('FuelID', (int) $request->fuel_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date_recorded', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_recorded', '<=', $request->date_to);
        }

        $logs  = $query->paginate(20)->withQueryString();
        $pumps = Pump::orderBy('pump_name')->get();
        $fuels = Fuel::orderBy('fuel_name')->get();

        return view('totalizer.index', compact('logs', 'pumps', 'fuels'));
    }

    public function export(Request $request)
    {
        $query = DB::table('totalizer_logs_view')
            ->orderBy('date_recorded', 'desc')
            ->orderBy('closed_at', 'desc');

        if ($request->filled('pump_id')) {
            $query->where('PumpID', (int) $request->pump_id);
        }
        if ($request->filled('fuel_id')) {
            $query->where('FuelID', (int) $request->fuel_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date_recorded', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_recorded', '<=', $request->date_to);
        }

        $logs = $query->get();

        $header = ['Date Recorded', 'Pump', 'Fuel Type', 'Closing Reading (L)'];
        $rows   = $logs->map(fn($log) => [
            $log->date_recorded,
            $log->pump_name,
            $log->fuel_name,
            number_format($log->reading, 3),
        ]);

        return $this->streamCsv('totalizer_log', $header, $rows);
    }

    private function streamCsv(string $filename, array $header, $rows)
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}_" . now()->format('Ymd_His') . ".csv\"",
        ];

        return response()->stream(function () use ($header, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $header);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, $headers);
    }
}