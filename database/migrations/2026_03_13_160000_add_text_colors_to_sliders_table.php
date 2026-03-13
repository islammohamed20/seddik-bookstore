<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (! Schema::hasColumn('sliders', 'title_color_ar')) {
                $table->string('title_color_ar', 20)->nullable()->after('title_ar');
            }
            if (! Schema::hasColumn('sliders', 'subtitle_color_ar')) {
                $table->string('subtitle_color_ar', 20)->nullable()->after('subtitle_ar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (Schema::hasColumn('sliders', 'title_color_ar')) {
                $table->dropColumn('title_color_ar');
            }
            if (Schema::hasColumn('sliders', 'subtitle_color_ar')) {
                $table->dropColumn('subtitle_color_ar');
            }
        });
    }
};
