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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price_inside_assiut', 10, 2)->nullable()->after('price');
            $table->decimal('price_outside_assiut', 10, 2)->nullable()->after('price_inside_assiut');
            $table->decimal('sale_price_inside_assiut', 10, 2)->nullable()->after('sale_price');
            $table->decimal('sale_price_outside_assiut', 10, 2)->nullable()->after('sale_price_inside_assiut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'price_inside_assiut',
                'price_outside_assiut',
                'sale_price_inside_assiut',
                'sale_price_outside_assiut'
            ]);
        });
    }
};
