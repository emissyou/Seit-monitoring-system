<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_credits', function (Blueprint $table) {
            $table->id('SalesCreditID');

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
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_credits');
    }
};