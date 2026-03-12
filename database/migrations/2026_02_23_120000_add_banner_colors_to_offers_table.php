<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('banner_color_from', 7)->nullable()->after('banner_image');
            $table->string('banner_color_to', 7)->nullable()->after('banner_color_from');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['banner_color_from', 'banner_color_to']);
        });
    }
};
