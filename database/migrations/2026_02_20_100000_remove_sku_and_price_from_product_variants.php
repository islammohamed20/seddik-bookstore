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
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                // Drop SKU and price columns if they exist
                if (Schema::hasColumn('product_variants', 'sku')) {
                    $table->dropUnique(['sku']);
                    $table->dropColumn('sku');
                }
                if (Schema::hasColumn('product_variants', 'price')) {
                    $table->dropColumn('price');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->string('sku')->nullable()->unique()->after('product_id');
                $table->decimal('price', 10, 2)->nullable()->after('sku');
            });
        }
    }
};
