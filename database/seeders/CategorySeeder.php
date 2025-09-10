<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name_ar' => 'الطب العام', 'name_en' => 'General Medicine', 'description_ar' => 'دروس في الطب العام', 'is_active' => true],
            ['name_ar' => 'علم التشريح', 'name_en' => 'Anatomy', 'description_ar' => 'مواد علم التشريح', 'is_active' => true],
            ['name_ar' => 'علم الأدوية', 'name_en' => 'Pharmacology', 'description_ar' => 'محتوى علم الأدوية', 'is_active' => true],
        ];

        foreach ($items as $i => $data) {
            Category::updateOrCreate(
                ['name_ar' => $data['name_ar']],
                array_merge($data, ['sort_order' => $i])
            );
        }
    }
}
