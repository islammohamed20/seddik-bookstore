<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_variants')) {
            return;
        }

        // Drop the old EAV tables — they are empty and unused
        Schema::dropIfExists('product_variant_attributes');
        Schema::dropIfExists('product_attribute_values');

        // Restructure product_variants table with JSON attribute_combination
        Schema::table('product_variants', function (Blueprint $table) {
            // Add the JSON attribute combination column
            $table->json('attribute_combination')->after('product_id');

            // Fix stock_quantity from decimal to unsigned int
            $table->unsignedInteger('stock_quantity_new')->default(0)->after('price');

            // Add sale_price for variant-level discounts
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');

            // Add weight for shipping
            $table->decimal('weight', 8, 3)->nullable()->after('sale_price');

            // Add sort_order
            $table->unsignedInteger('sort_order')->default(0)->after('image');

            // Add indexes
            $table->index(['product_id', 'is_active']);
        });

        // Migrate stock_quantity data (decimal → int) then drop old column
        DB::statement('UPDATE product_variants SET stock_quantity_new = CAST(stock_quantity AS UNSIGNED)');

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('stock_quantity');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->renameColumn('stock_quantity_new', 'stock_quantity');
        });

        // Add unique compound index for sku (if not null)
        // SKU should be unique per product
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unique('sku');
        });

        // Add has_variants flag to products table for fast filtering
        if (!Schema::hasColumn('products', 'has_variants')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_variants')->default(false)->after('product_type');
            });
        }

        // Add variant_id to order_items for tracking which variant was ordered
        if (!Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
                $table->foreign('variant_id')
                      ->references('id')
                      ->on('product_variants')
                      ->nullOnDelete();
            });
        }

        // Sync has_variants flag based on product_type
        DB::statement("UPDATE products SET has_variants = (product_type = 'variable')");
    }

    public function down(): void
    {
        // Remove has_variants from products
        if (Schema::hasColumn('products', 'has_variants')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('has_variants');
            });
        }

        // Remove variant_id from order_items
        if (Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['variant_id']);
                $table->dropColumn('variant_id');
            });
        }

        // Reverse product_variants changes
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropIndex(['product_id', 'is_active']);
            $table->dropColumn(['attribute_combination', 'sale_price', 'weight', 'sort_order']);
        });

        // Recreate the old EAV tables
        Schema::create('product_variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
            $table->text('value');
            $table->timestamps();
        });
    }
};
