<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('fuel_id')->constrained()->onDelete('cascade');

            $table->decimal('quantity', 8, 2);   // liters
            $table->decimal('price', 8, 2);
            $table->decimal('amount', 10, 2);
            $table->decimal('balance', 10, 2);

            $table->date('credit_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};