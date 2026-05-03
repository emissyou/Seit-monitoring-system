<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sales_discounts');

        Schema::create('sales_discounts', function (Blueprint $table) {
            $table->id('salesDiscountID');

            $table->unsignedBigInteger('SalesID');
            $table->foreign('SalesID')
                  ->references('SalesID')
                  ->on('sales')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('DiscountID')->nullable();
            $table->foreign('DiscountID')
                  ->references('DiscountID')
                  ->on('discounts')
                  ->nullOnDelete();

            $table->unsignedBigInteger('FuelID')->nullable();
            $table->foreign('FuelID')
                  ->references('FuelID')
                  ->on('fuels')
                  ->nullOnDelete();

            $table->unsignedBigInteger('CustomerID')->nullable();
            $table->foreign('CustomerID')
                  ->references('CustomerID')
                  ->on('customers')
                  ->nullOnDelete();

            $table->decimal('liters', 12, 3)->default(0);
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('discount_per_liter', 10, 2)->default(0);
            $table->decimal('discount_sale', 12, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_discounts');
    }
};