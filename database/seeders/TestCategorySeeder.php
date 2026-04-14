<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'womens-health',    'sort_order' => 1,  'en' => "Women's Health",          'ar' => 'صحة المرأة'],
            ['slug' => 'mens-health',      'sort_order' => 2,  'en' => "Men's Health",             'ar' => 'صحة الرجل'],
            ['slug' => 'premarital',       'sort_order' => 3,  'en' => 'Premarital Checks',        'ar' => 'فحوصات ما قبل الزواج'],
            ['slug' => 'cancer',           'sort_order' => 4,  'en' => 'Cancer Screening',         'ar' => 'الكشف المبكر عن السرطان'],
            ['slug' => 'thyroid',          'sort_order' => 5,  'en' => 'Thyroid',                  'ar' => 'الغدة الدرقية'],
            ['slug' => 'allergy',          'sort_order' => 6,  'en' => 'Allergy',                  'ar' => 'الحساسية'],
            ['slug' => 'gut-health',       'sort_order' => 7,  'en' => 'Gut Health',               'ar' => 'صحة الجهاز الهضمي'],
            ['slug' => 'kids',             'sort_order' => 8,  'en' => 'Kids',                     'ar' => 'الأطفال'],
            ['slug' => 'nutrition',        'sort_order' => 9,  'en' => 'Nutrition & Metabolism',   'ar' => 'التغذية والتمثيل الغذائي'],
            ['slug' => 'hormones',         'sort_order' => 10, 'en' => 'Hormones',                 'ar' => 'الهرمونات'],
            ['slug' => 'diabetes',         'sort_order' => 11, 'en' => 'Diabetes',                 'ar' => 'السكري'],
            ['slug' => 'heart',            'sort_order' => 12, 'en' => 'Heart Health',             'ar' => 'صحة القلب'],
            ['slug' => 'bone-joint',       'sort_order' => 13, 'en' => 'Bone & Joint',             'ar' => 'العظام والمفاصل'],
            ['slug' => 'sports',           'sort_order' => 14, 'en' => 'Sports Performance',       'ar' => 'الأداء الرياضي'],
            ['slug' => 'general-wellness', 'sort_order' => 15, 'en' => 'General Wellness',         'ar' => 'العافية العامة'],
        ];

        foreach ($categories as $cat) {
            $id = DB::table('test_categories')->insertGetId([
                'slug'       => $cat['slug'],
                'is_active'  => true,
                'sort_order' => $cat['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('test_category_translations')->insert([
                ['test_category_id' => $id, 'locale' => 'en', 'name' => $cat['en'], 'created_at' => now(), 'updated_at' => now()],
                ['test_category_id' => $id, 'locale' => 'ar', 'name' => $cat['ar'], 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
