<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            $exists = DB::table('categories')
                ->where('slug', 'teck-toys-products')
                ->exists();

            if (! $exists) {
                DB::table('categories')->insert([
                    'parent_id' => null,
                    'slug' => 'teck-toys-products',
                    'name_ar' => 'منتجات تيك تويز',
                    'name_en' => 'Teck Toys Products',
                    'description_ar' => null,
                    'description_en' => null,
                    'icon' => 'fas fa-robot',
                    'image' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'sort_order' => 0,
                    'seo_title_ar' => 'منتجات تيك تويز',
                    'seo_title_en' => 'Teck Toys Products',
                    'seo_description_ar' => null,
                    'seo_description_en' => null,
                    'seo_keywords' => 'teck toys,tech toys,ألعاب تقنية',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories')) {
            DB::table('categories')
                ->where('slug', 'teck-toys-products')
                ->delete();
        }
    }
};

