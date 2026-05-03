<?php
// FILE: database/migrations/xxxx_xx_xx_create_credit_payments_table.php
// Rename this file with the current date, e.g.:
//   2026_05_01_000001_create_credit_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only creates the table if it does not already exist
        if (!Schema::hasTable('credit_payments')) {
            Schema::create('credit_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('credit_id')
                      ->constrained('credits')
                      ->onDelete('cascade');
                $table->foreignId('customer_id')
                      ->constrained('customers')
                      ->onDelete('cascade');
                $table->date('payment_date');
                $table->decimal('amount_paid', 10, 2);
                $table->string('note')->nullable();
                $table->timestamps();
            });
        }

        // Also ensure the credits table has the balance column
        if (Schema::hasTable('credits') && !Schema::hasColumn('credits', 'balance')) {
            Schema::table('credits', function (Blueprint $table) {
                $table->decimal('balance', 10, 2)->default(0)->after('amount');
            });
        }

        // Ensure amount_paid column exists on credits
        if (Schema::hasTable('credits') && !Schema::hasColumn('credits', 'amount_paid')) {
            Schema::table('credits', function (Blueprint $table) {
                $table->decimal('amount_paid', 10, 2)->default(0)->after('balance');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_payments');
    }
};