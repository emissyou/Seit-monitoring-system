<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('CustomerID');                        // ERD: PK CustomerID
            $table->string('First_name');
            $table->string('Middle_name')->nullable();
            $table->string('Last_name');
            $table->string('contact_number');
            $table->text('address');
            $table->boolean('is_active')->default(true);    // ERD: is_active
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};