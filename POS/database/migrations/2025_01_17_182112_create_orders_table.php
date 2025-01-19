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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id'); // Primary key
            $table->unsignedBigInteger('customer_id')->nullable(); // Foreign key
            $table->unsignedBigInteger('user_id')->nullable(); // reference admin or cashier
            $table->date('order_date'); // Order date
            $table->decimal('total_amount', 10, 2); // Total amount
            $table->decimal('discount', 10, 2)->default(0); // Discount
            $table->decimal('tax', 10, 2)->default(0); // Discount
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
