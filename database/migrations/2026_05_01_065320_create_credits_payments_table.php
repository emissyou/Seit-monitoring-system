<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id('PaymentID');

            // MUST MATCH credits.CreditID EXACTLY
            $table->unsignedBigInteger('CreditID');

            $table->foreign('CreditID')
                  ->references('CreditID')
                  ->on('credits')
                  ->onDelete('cascade');

            $table->date('payment_date');
            $table->decimal('amount_paid', 10, 2);
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_payments');
    }
};