<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id'); // Primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Price with precision
            $table->string('image_url')->nullable();
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('category_id')->nullable(); // Foreign key (nullable) 
            $table->unsignedBigInteger('supplier_id')->nullable(); // Foreign key (nullable)
            $table->unsignedBigInteger('created_by')->nullable(); // Track who created the product
            $table->unsignedBigInteger('updated_by')->nullable(); // Track who updated the product
            $table->unsignedBigInteger('deleted_by')->nullable(); // Track who deleted the product
            $table->timestamps();

            // New Columns
            $table->string('sku')->unique(); // SKU (unique for each product)  
            $table->unsignedInteger('low_stock_threshold')->default(5); // Low stock threshold (default 5)

            // Foreign key constraints for audit columns
            $table->foreign('created_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');

            // Foreign key constraints
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('set null'); // Make category_id nullable
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('set null'); // supplier_id already nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
