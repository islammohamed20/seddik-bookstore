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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->string('banner_desktop')->nullable()->after('color_end');
            $table->string('banner_mobile')->nullable()->after('banner_desktop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['banner_desktop', 'banner_mobile']);
            $table->string('image')->nullable()->after('color_end');
        });
    }
};
