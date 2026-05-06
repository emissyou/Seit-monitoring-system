<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreditSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Assumes CustomerID 1–4 exist (from CustomerSeeder)
        // Assumes FuelID=1, PumpFuelID=1 exist

        DB::table('credits')->insert([
            // Customer 1 — Juan Dela Cruz: 2 credits, 1 unpaid 1 paid
            [
                'CustomerID'      => 1,
                'FuelID'          => 1,
                'PumpFuelID'      => 1,
                'Quantity'        => 20.000,
                'price_per_liter' => 62.50,
                'discount_amount' => 0.00,
                'credit_date'     => Carbon::today()->subDays(3)->toDateString(),
                'status'          => 'unpaid',
                'archived'        => false,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'CustomerID'      => 1,
                'FuelID'          => 1,
                'PumpFuelID'      => 1,
                'Quantity'        => 15.000,
                'price_per_liter' => 62.50,
                'discount_amount' => 0.00,
                'credit_date'     => Carbon::today()->subDays(2)->toDateString(),
                'status'          => 'paid',
                'archived'        => false,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // Customer 2 — Maria Santos: 1 unpaid credit
            [
                'CustomerID'      => 2,
                'FuelID'          => 1,
                'PumpFuelID'      => 1,
                'Quantity'        => 30.000,
                'price_per_liter' => 62.50,
                'discount_amount' => 0.00,
                'credit_date'     => Carbon::today()->subDays(2)->toDateString(),
                'status'          => 'unpaid',
                'archived'        => false,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // Customer 3 — Pedro Villanueva: 1 unpaid with discount
            [
                'CustomerID'      => 3,
                'FuelID'          => 1,
                'PumpFuelID'      => 1,
                'Quantity'        => 25.000,
                'price_per_liter' => 63.00,
                'discount_amount' => 1.50,
                'credit_date'     => Carbon::yesterday()->toDateString(),
                'status'          => 'unpaid',
                'archived'        => false,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // Customer 4 — Ana Garcia: 1 partial paid credit
            [
                'CustomerID'      => 4,
                'FuelID'          => 1,
                'PumpFuelID'      => 1,
                'Quantity'        => 40.000,
                'price_per_liter' => 63.00,
                'discount_amount' => 0.00,
                'credit_date'     => Carbon::yesterday()->toDateString(),
                'status'          => 'unpaid',
                'archived'        => false,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);
    }
}