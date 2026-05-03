<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pump_fuels', function (Blueprint $table) {
            $table->id('pumpFuelID');

            $table->unsignedBigInteger('pumpID');
            $table->foreign('pumpID')
                  ->references('pumpID')
                  ->on('pumps')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('fuelID');
            $table->foreign('fuelID')
                  ->references('fuelID')
                  ->on('fuels')
                  ->cascadeOnDelete();

            $table->decimal('totalizer_reading', 12, 3)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pump_fuels');
    }
};