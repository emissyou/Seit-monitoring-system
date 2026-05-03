<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('totalizer_logs', function (Blueprint $table) {
            $table->id('TotalizerID');
            $table->unsignedBigInteger('PumpFuelID');
            $table->decimal('reading', 12, 3);
            $table->date('date_recorded');
            $table->timestamps();

            $table->foreign('PumpFuelID')->references('PumpFuelID')->on('pump_fuels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('totalizer_logs');
    }
};