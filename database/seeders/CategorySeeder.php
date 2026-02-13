<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'school-supplies',
                'name_ar' => 'مستلزمات مدرسية',
                'name_en' => 'School Supplies',
                'description_ar' => 'كل ما يحتاجه طفلك للمدرسة من أدوات وكتب',
                'description_en' => 'Everything your child needs for school',
                'icon' => 'fa-book-open',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'leather-products',
                'name_ar' => 'منتجات جلدية',
                'name_en' => 'Leather Products',
                'description_ar' => 'حقائب وأحزمة ومحافظ جلدية فاخرة',
                'description_en' => 'Premium leather bags, belts and wallets',
                'icon' => 'fa-briefcase',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'study-notes',
                'name_ar' => 'مذكرات الدراسة',
                'name_en' => 'Study Notes',
                'description_ar' => 'مذكرات ومراجعات لجميع المراحل التعليمية',
                'description_en' => 'Study notes and reviews for all educational stages',
                'icon' => 'fa-file-alt',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'montessori-toys',
                'name_ar' => 'ألعاب مونتيسوري',
                'name_en' => 'Montessori Toys',
                'description_ar' => 'ألعاب تعليمية بنظام مونتيسوري لتنمية مهارات الأطفال',
                'description_en' => 'Educational Montessori toys for child development',
                'icon' => 'fa-puzzle-piece',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'kids-toys',
                'name_ar' => 'ألعاب أطفال',
                'name_en' => 'Kids Toys',
                'description_ar' => 'ألعاب ممتعة وآمنة لجميع الأعمار',
                'description_en' => 'Fun and safe toys for all ages',
                'icon' => 'fa-child',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'slug' => 'bingo',
                'name_ar' => 'بينجو',
                'name_en' => 'Bingo',
                'description_ar' => 'منتجات بينجو الحصرية والمميزة',
                'description_en' => 'Exclusive Bingo products',
                'icon' => 'fa-star',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
