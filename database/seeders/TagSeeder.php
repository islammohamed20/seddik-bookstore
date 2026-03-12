<?php

namespace Database\Seeders;

use App\Models\TagGroup;
use App\Models\TagOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name_ar' => 'السن',
                'name_en' => 'Age',
                'slug' => 'age',
                'options' => ['3 سنوات', '5 سنوات', '7 سنوات', '9 سنوات', '12 سنة'],
            ],
        ];

        foreach ($groups as $g) {
            $group = TagGroup::firstOrCreate(
                ['slug' => $g['slug']],
                [
                    'name_ar' => $g['name_ar'] ?? null,
                    'name_en' => $g['name_en'] ?? null,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );

            foreach ($g['options'] as $idx => $nameAr) {
                $slug = Str::slug($nameAr) ?: Str::slug($g['slug'] . '-' . ($idx + 1));
                TagOption::firstOrCreate(
                    ['tag_group_id' => $group->id, 'slug' => $slug],
                    [
                        'name_ar' => $nameAr,
                        'name_en' => null,
                        'is_active' => true,
                        'sort_order' => $idx,
                    ]
                );
            }
        }
    }
}

