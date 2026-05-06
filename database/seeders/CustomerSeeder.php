<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('customers')->insert([
            [
                'First_name'     => 'Juan',
                'Middle_name'    => 'Santos',
                'Last_name'      => 'Dela Cruz',
                'contact_number' => '09171234567',
                'address'        => 'Brgy. Poblacion, Cagayan de Oro City',
                'is_active'      => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'First_name'     => 'Maria',
                'Middle_name'    => 'Reyes',
                'Last_name'      => 'Santos',
                'contact_number' => '09281234567',
                'address'        => 'Brgy. Macabalan, Cagayan de Oro City',
                'is_active'      => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'First_name'     => 'Pedro',
                'Middle_name'    => null,
                'Last_name'      => 'Villanueva',
                'contact_number' => '09391234567',
                'address'        => 'Brgy. Carmen, Cagayan de Oro City',
                'is_active'      => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'First_name'     => 'Ana',
                'Middle_name'    => 'Lim',
                'Last_name'      => 'Garcia',
                'contact_number' => '09501234567',
                'address'        => 'Brgy. Nazareth, Cagayan de Oro City',
                'is_active'      => true,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'First_name'     => 'Carlo',
                'Middle_name'    => null,
                'Last_name'      => 'Mendoza',
                'contact_number' => '09611234567',
                'address'        => 'Brgy. Consolacion, Cagayan de Oro City',
                'is_active'      => false, // archived
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);
    }
}