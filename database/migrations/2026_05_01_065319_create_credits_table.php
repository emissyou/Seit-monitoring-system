<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id('CreditID'); // BIGINT UNSIGNED PRIMARY KEY

            // Make sure referenced columns are ALSO unsignedBigInteger + primary
            $table->unsignedBigInteger('CustomerID');
            $table->unsignedBigInteger('FuelID');
            $table->unsignedBigInteger('PumpFuelID')->nullable();

            // FOREIGN KEYS
            $table->foreign('CustomerID')
                  ->references('CustomerID')
                  ->on('customers')
                  ->cascadeOnDelete();

            $table->foreign('FuelID')
                  ->references('FuelID')
                  ->on('fuels')
                  ->cascadeOnDelete();

            $table->foreign('PumpFuelID')
                  ->references('PumpFuelID')
                  ->on('pump_fuels')
                  ->nullOnDelete();

            $table->decimal('Quantity', 8, 3);
            $table->decimal('price_per_liter', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->date('credit_date');
            $table->string('status')->default('unpaid');
            $table->boolean('archived')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};