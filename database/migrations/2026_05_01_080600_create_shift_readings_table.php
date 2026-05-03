<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_readings', function (Blueprint $table) {
            $table->id('ShiftReadingID');
            $table->foreignId('ShiftID')
                  ->constrained('shifts', 'ShiftID')
                  ->cascadeOnDelete();
            $table->foreignId('PumpID')
                  ->constrained('pumps', 'pumpID')
                  ->cascadeOnDelete();
            $table->foreignId('FuelID')
                  ->constrained('fuels', 'FuelID')
                  ->cascadeOnDelete();
            $table->decimal('opening_reading', 12, 3)->default(0);
            $table->decimal('closing_reading', 12, 3)->nullable();
            $table->decimal('price_per_liter', 10, 2)->nullable();  // price at time of closing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_readings');
    }
};