<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('discounts')->insert([
            // Per-liter discount for Customer 1
            [
                'CustomerID'     => 1,
                'discount_type'  => 'per_liter',
                'discount_value' => 1.50,
                'start_date'     => Carbon::today()->subDays(7)->toDateString(),
                'end_date'       => Carbon::today()->addDays(23)->toDateString(),
                'description'    => 'Loyalty discount for regular customer',
                'is_active'      => true,
                'archived'       => false,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // Per-liter discount for Customer 2
            [
                'CustomerID'     => 2,
                'discount_type'  => 'per_liter',
                'discount_value' => 2.00,
                'start_date'     => Carbon::today()->subDays(5)->toDateString(),
                'end_date'       => Carbon::today()->addDays(25)->toDateString(),
                'description'    => 'Fleet account discount',
                'is_active'      => true,
                'archived'       => false,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // Fixed amount discount — no specific customer (general promo)
            [
                'CustomerID'     => null,
                'discount_type'  => 'fixed_amount',
                'discount_value' => 50.00,
                'start_date'     => Carbon::today()->toDateString(),
                'end_date'       => Carbon::today()->addDays(7)->toDateString(),
                'description'    => 'Weekly promo discount',
                'is_active'      => true,
                'archived'       => false,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // Expired discount — archived
            [
                'CustomerID'     => 3,
                'discount_type'  => 'per_liter',
                'discount_value' => 1.00,
                'start_date'     => Carbon::today()->subDays(30)->toDateString(),
                'end_date'       => Carbon::today()->subDays(1)->toDateString(),
                'description'    => 'Expired promo',
                'is_active'      => false,
                'archived'       => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);
    }
}