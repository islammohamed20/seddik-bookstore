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
        // 1. Create Product Variants Table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('stock_quantity')->default(0); // Track inventory per variant
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 2. Create Variant Attributes Pivot Table
        // Links a Variant to Attribute Values (e.g., Variant 1 has Color: Red, Size: L)
        Schema::create('product_variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
            $table->string('value'); // The actual value (e.g., "Red", "Large")
            $table->timestamps();
            
            // Ensure unique combination per variant
            $table->unique(['product_variant_id', 'product_attribute_id'], 'variant_attr_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_attributes');
        Schema::dropIfExists('product_variants');
    }
};
