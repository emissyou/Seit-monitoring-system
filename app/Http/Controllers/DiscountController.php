<?php

namespace App\Http\Controllers;

use App\Models\Discount;        // points to `discounts` table — used for WRITES
use App\Models\DiscountView;    // points to `discount_summary_view` — used for READS
use App\Models\Customer;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // ── READ: index ────────────────────────────────────────────────────────
    // View already contains: customer_name
    public function index()
    {
        $discounts = DiscountView::latest('DiscountID')->paginate(15);
        $customers = Customer::orderBy('First_name')->get();

        return view('discounts.index', compact('discounts', 'customers'));
    }

    // ── READ: export CSV ───────────────────────────────────────────────────
    public function export(Request $request)
    {
        $query = DiscountView::latest('DiscountID');

        if ($request->filled('customer_id')) {
            $query->where('CustomerID', $request->customer_id);
        }
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $discounts = $query->get();

        $header = [
            'Start Date',
            'End Date',
            'Customer',
            'Discount Type',
            'Value',
            'Description',
            'Status',
        ];

        $rows = $discounts->map(fn($d) => [
            optional($d->start_date)->format('Y-m-d'),
            optional($d->end_date)->format('Y-m-d'),
            $d->customer_name,                          // from view
            ucfirst(str_replace('_', ' ', $d->discount_type)),
            $d->discount_type === 'percentage'
                ? number_format((float) $d->discount_value, 2) . '%'
                : '₱' . number_format((float) $d->discount_value, 2),
            $d->description ?? '—',
            $d->is_active ? 'Active' : 'Inactive',
        ]);

        return $this->streamCsv('discount_logs', $header, $rows);
    }

    // ── WRITE: store ───────────────────────────────────────────────────────
    // Views are read-only — write directly to the `discounts` table via Discount model
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

    // ── WRITE: archive ─────────────────────────────────────────────────────
    public function archive($id)
    {
        Discount::where('DiscountID', $id)->update(['archived' => true]);

        return redirect()->route('discounts.index')
                         ->with('success', 'Discount archived successfully.');
    }

    // ── WRITE: delete ──────────────────────────────────────────────────────
    public function destroy($id)
    {
        Discount::where('DiscountID', $id)->delete();

        return redirect()->route('discounts.index')
                         ->with('success', 'Discount deleted successfully.');
    }

    // ── Shared CSV streamer ────────────────────────────────────────────────

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