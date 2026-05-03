<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('StockID');
            $table->unsignedBigInteger('FuelID');
            $table->unsignedBigInteger('TankID');
            $table->enum('type', ['IN', 'OUT']);
            $table->decimal('current_stock', 12, 3)->default(0);
            $table->date('date');
            $table->timestamps();

            $table->foreign('FuelID')->references('FuelID')->on('fuels')->onDelete('cascade');
            $table->foreign('TankID')->references('TankID')->on('tanks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};