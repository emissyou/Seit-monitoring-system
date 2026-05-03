<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sales
        Schema::create('sales', function (Blueprint $table) {
            $table->id('SalesID');                          // ERD: PK SalesID

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

            $table->date('date');
            $table->decimal('totalizer_liters', 12, 3)->default(0);
            $table->decimal('computed_gross_sales', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total_credit', 12, 2)->default(0);
            $table->decimal('computed_net_sales', 12, 2)->default(0);
            $table->decimal('computed_cash_in_hand', 12, 2)->default(0);
            $table->timestamps();
        });

        // 2. SalesDiscount — depends on sales, discounts, fuels, customers
        Schema::create('sales_discounts', function (Blueprint $table) {
            $table->id('salesDiscountID');                  // ERD: PK salesDiscountID

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

        // 3. SalesCredit — depends on sales, credits, customers
        Schema::create('sales_credits', function (Blueprint $table) {
            $table->id('SalesCreditID');                    // ERD: PK SalesCreditID

            $table->unsignedBigInteger('SalesID');
            $table->foreign('SalesID')
                  ->references('SalesID')
                  ->on('sales')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('CreditID')->nullable();
            $table->foreign('CreditID')
                  ->references('CreditID')
                  ->on('credits')
                  ->nullOnDelete();

            $table->unsignedBigInteger('CustomerID')->nullable();
            $table->foreign('CustomerID')
                  ->references('CustomerID')
                  ->on('customers')
                  ->nullOnDelete();

            $table->decimal('liters', 12, 3)->default(0);
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('retail_sale', 12, 2)->default(0);
            $table->boolean('discounted')->default(false);
            $table->decimal('discounted_sale', 12, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 4. SalesDetails — LAST (depends on sales AND sales_credits)
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id('SaleDetailID');                     // ERD: PK SaleDetailID

            $table->unsignedBigInteger('SalesID');
            $table->foreign('SalesID')
                  ->references('SalesID')
                  ->on('sales')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('FuelID');
            $table->foreign('FuelID')
                  ->references('FuelID')
                  ->on('fuels')
                  ->cascadeOnDelete();

            $table->decimal('salesDiscount', 12, 2)->default(0);

            $table->unsignedBigInteger('SalesCreditID')->nullable();
            $table->foreign('SalesCreditID')
                  ->references('SalesCreditID')
                  ->on('sales_credits')
                  ->nullOnDelete();

            $table->decimal('Price_per_Liter', 10, 2)->default(0);
            $table->decimal('Liters', 12, 3)->default(0);
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