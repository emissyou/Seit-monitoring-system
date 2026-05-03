<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sales — one record per shift (summary of the shift's sales)
        Schema::create('sales', function (Blueprint $table) {
            $table->id('SalesID');
            $table->foreignId('ShiftID')
                  ->constrained('shifts', 'ShiftID')
                  ->cascadeOnDelete();
            $table->foreignId('PumpID')
                  ->constrained('pumps', 'pumpID')
                  ->cascadeOnDelete();
            $table->date('date');
            $table->decimal('totalizer_liters', 12, 3)->default(0);
            $table->decimal('computed_gross_sales', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total_credit', 12, 2)->default(0);
            $table->decimal('computed_net_sales', 12, 2)->default(0);
            $table->decimal('computed_cash_in_hand', 12, 2)->default(0);
            $table->timestamps();
        });

        // SalesDetails — one row per PumpFuel reading pair
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id('SaleDetailID');
            $table->foreignId('SalesID')
                  ->constrained('sales', 'SalesID')
                  ->cascadeOnDelete();
            $table->foreignId('FuelID')
                  ->constrained('fuels', 'FuelID')
                  ->cascadeOnDelete();
            $table->decimal('salesDiscount', 12, 2)->default(0);
            $table->foreignId('SalesCreditID')->nullable()->constrained('sales_credits', 'SalesCreditID')->nullOnDelete();
            $table->decimal('Price_per_Liter', 10, 2)->default(0);
            $table->decimal('Liters', 12, 3)->default(0);
            $table->timestamps();
        });

        // SalesDiscount
        Schema::create('sales_discounts', function (Blueprint $table) {
            $table->id('salesDiscountID');
            $table->foreignId('SalesID')
                  ->constrained('sales', 'SalesID')
                  ->cascadeOnDelete();
            $table->foreignId('DiscountID')
                  ->nullable()
                  ->constrained('discounts', 'DiscountID')
                  ->nullOnDelete();
            $table->decimal('liters', 12, 3)->default(0);
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('discount_per_liter', 10, 2)->default(0);
            $table->decimal('discount_sale', 12, 2)->default(0);
            $table->foreignId('FuelID')->nullable()->constrained('fuels', 'FuelID')->nullOnDelete();
            $table->foreignId('CustomerID')->nullable()->constrained('customers', 'CustomerID')->nullOnDelete();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // SalesCredit
        Schema::create('sales_credits', function (Blueprint $table) {
            $table->id('SalesCreditID');
            $table->foreignId('SalesID')
                  ->constrained('sales', 'SalesID')
                  ->cascadeOnDelete();
            $table->foreignId('CreditID')
                  ->nullable()
                  ->constrained('credits', 'CreditID')
                  ->nullOnDelete();
            $table->foreignId('CustomerID')->nullable()->constrained('customers', 'CustomerID')->nullOnDelete();
            $table->decimal('liters', 12, 3)->default(0);
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('retail_sale', 12, 2)->default(0);
            $table->boolean('discounted')->default(false);
            $table->decimal('discounted_sale', 12, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_details');
        Schema::dropIfExists('sales_discounts');
        Schema::dropIfExists('sales_credits');
        Schema::dropIfExists('sales');
    }
};