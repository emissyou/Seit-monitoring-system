<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id('CreditID');

            $table->unsignedBigInteger('CustomerID');
            $table->foreign('CustomerID')
                  ->references('CustomerID')
                  ->on('customers')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('FuelID');
            $table->foreign('FuelID')
                  ->references('FuelID')
                  ->on('fuels')
                  ->cascadeOnDelete();

            $table->decimal('Quantity', 8, 3);
            $table->date('credit_date');
            $table->string('status')->default('unpaid'); // unpaid | partial | paid
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};