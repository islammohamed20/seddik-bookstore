<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (! Schema::hasColumn('categories', 'color_start')) {
                    $table->string('color_start', 9)->nullable()->after('icon');
                }
                if (! Schema::hasColumn('categories', 'color_end')) {
                    $table->string('color_end', 9)->nullable()->after('color_start');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'color_start')) {
                    $table->dropColumn('color_start');
                }
                if (Schema::hasColumn('categories', 'color_end')) {
                    $table->dropColumn('color_end');
                }
            });
        }
    }
};

