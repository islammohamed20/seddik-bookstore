<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $exists = DB::table('pages')
            ->where('slug', 'teck-toys-products')
            ->exists();

        if (! $exists) {
            DB::table('pages')->insert([
                'slug' => 'teck-toys-products',
                'title_ar' => 'منتجات تيك تويز',
                'title_en' => 'Teck Toys Products',
                'content_ar' => 'صفحة مخصصة لعرض وإدارة منتجات تيك تويز.',
                'content_en' => 'Special page to manage Teck Toys products.',
                'seo_title_ar' => 'منتجات تيك تويز',
                'seo_title_en' => 'Teck Toys Products',
                'seo_description_ar' => null,
                'seo_description_en' => null,
                'seo_keywords' => 'teck toys,tech toys,ألعاب تقنية',
                'is_published' => true,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        DB::table('pages')
            ->where('slug', 'teck-toys-products')
            ->delete();
    }
};

