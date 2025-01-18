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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id'); // Primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Price with precision
            $table->unsignedBigInteger('category_id')->nullable(); // Foreign key (nullable) 
            $table->string('image_url')->nullable();
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('supplier_id')->nullable(); // Foreign key (nullable)
            $table->timestamps();

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
