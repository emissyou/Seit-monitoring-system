<?php
// FILE: database/migrations/2026_05_01_000001_fix_credits_and_create_credit_payments.php
//
// HOW TO USE:
//   1. Copy this file into your database/migrations/ folder
//   2. Run: php artisan migrate
//
// This migration is safe to run even if some columns already exist.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Patch the credits table ────────────────────────────────────────
        Schema::table('credits', function (Blueprint $table) {
            if (!Schema::hasColumn('credits', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('credits', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0)->after('balance');
            }
            if (!Schema::hasColumn('credits', 'payment_status')) {
                $table->string('payment_status', 20)->default('unpaid')->after('amount_paid');
            }
        });

        // Back-fill any NULL values left from before the columns existed
        DB::table('credits')
            ->whereNull('amount_paid')
            ->update(['amount_paid' => 0]);

        DB::table('credits')
            ->whereNull('balance')
            ->update(['balance' => DB::raw('amount')]);

        DB::table('credits')
            ->whereNull('payment_status')
            ->orWhere('payment_status', '')
            ->update(['payment_status' => 'unpaid']);

        // ── 2. Create credit_payments table if missing ────────────────────────
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
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_payments');

        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn(['balance', 'amount_paid', 'payment_status']);
        });
    }
};