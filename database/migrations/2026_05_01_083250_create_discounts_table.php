<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('discount_type');           // per_liter, fixed_amount, percentage
            $table->decimal('discount_value', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};