<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('subtitle_ar')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->text('short_description_ar')->nullable();
            $table->text('short_description_en')->nullable();
            $table->longText('description_ar')->nullable();
            $table->longText('description_en')->nullable();
            $table->enum('type', [
                'school_supplies',
                'leather_products',
                'study_notes',
                'montessori_toys',
                'kids_toys',
                'bingo',
            ])->default('school_supplies')->index();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])
                ->default('in_stock')
                ->index();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_bingo')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('seo_title_ar')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->text('seo_description_ar')->nullable();
            $table->text('seo_description_en')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->json('extra_attributes')->nullable();
            $table->timestamps();

            $table->index(['category_id', 'brand_id'], 'products_category_brand_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
