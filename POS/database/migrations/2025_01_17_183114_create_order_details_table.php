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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('order_detail_id'); // Primary key
            $table->unsignedBigInteger('order_id')->nullable(); // Foreign key
            $table->unsignedBigInteger('product_id')->nullable(); // Foreign key
            $table->integer('quantity'); // Quantity of the product
            $table->decimal('price', 10, 2); // Price per product
            $table->decimal('subtotal', 10, 2); // Subtotal for the line item
            $table->decimal('discount', 10, 2)->default(0); // Discount
            $table->decimal('tax', 10, 2)->default(0); // Discount
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('set null');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
