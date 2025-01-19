<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id'); // Primary key
            $table->unsignedBigInteger('order_id')->nullable(); // Foreign key
            $table->enum('payment_method', ['cash', 'card', 'mobile payment']);
            $table->decimal('amount_paid', 10, 2); // Amount paid
            $table->decimal('change', 10, 2); // Change 
            $table->timestamps();

            // Foreign key constraint
            $table->foreign(columns: 'order_id')->references('order_id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
