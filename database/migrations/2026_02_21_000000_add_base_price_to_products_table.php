<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('product_type');
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
        });

        // Backfill from existing geographic prices when available.
        DB::statement("UPDATE products SET price = COALESCE(price_inside_assiut, price_outside_assiut, 0)");
        DB::statement("UPDATE products SET sale_price = COALESCE(sale_price_inside_assiut, sale_price_outside_assiut)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price', 'sale_price']);
        });
    }
};
