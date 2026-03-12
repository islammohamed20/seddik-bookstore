<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku', 100)->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('product_variants', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('sku');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('product_variants', 'sku')) {
                $table->dropColumn('sku');
            }
        });
    }
};

