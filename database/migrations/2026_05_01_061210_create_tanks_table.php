<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanks', function (Blueprint $table) {
            $table->id('TankID');
            $table->unsignedBigInteger('FuelID');
            $table->decimal('max_quantity',  12, 3);
            $table->decimal('current_stock', 12, 3)->default(0);
            $table->timestamps();

            $table->foreign('FuelID')->references('FuelID')->on('fuels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanks');
    }
};