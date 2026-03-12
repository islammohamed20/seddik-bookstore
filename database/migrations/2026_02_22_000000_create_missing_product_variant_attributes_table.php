<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_variant_attributes')) {
            Schema::create('product_variant_attributes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
                $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
                $table->string('value');
                $table->timestamps();

                $table->unique(['product_variant_id', 'product_attribute_id'], 'variant_attr_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_attributes');
    }
};

