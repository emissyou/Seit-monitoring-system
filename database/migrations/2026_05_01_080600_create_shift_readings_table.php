<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_readings', function (Blueprint $table) {
            $table->id('ShiftReadingID');                   // ERD: PK ShiftReadingID

            $table->unsignedBigInteger('ShiftID');
            $table->foreign('ShiftID')
                  ->references('ShiftID')
                  ->on('shifts')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('PumpID');
            $table->foreign('PumpID')
                  ->references('PumpID')
                  ->on('pumps')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('FuelID');
            $table->foreign('FuelID')
                  ->references('FuelID')
                  ->on('fuels')
                  ->cascadeOnDelete();

            $table->decimal('opening_reading', 12, 3)->default(0);
            $table->decimal('closing_reading', 12, 3)->nullable();
            $table->decimal('price_per_liter', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_readings');
    }
};