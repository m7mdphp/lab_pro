<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'slug'            => 'sheikh-zayed',
                'phone'           => '01000000001',
                'whatsapp'        => '01000000001',
                'sort_order'      => 1,
                'google_maps_url' => 'https://maps.google.com/',
                'latitude'        => 30.0444,
                'longitude'       => 31.2357,
                'en_name'         => 'Sheikh Zayed Branch',
                'en_city'         => 'Giza',
                'en_address'      => 'Rofayda Health Park, El Zohour, Dorra Square, El Sheikh Zayed',
                'en_hours'        => 'Sat–Thu: 8:00 AM – 11:00 PM | Fri: 10:00 AM – 6:00 PM',
                'ar_name'         => 'فرع الشيخ زايد',
                'ar_city'         => 'الجيزة',
                'ar_address'      => 'مجمع روفايدة الصحي، الزهور، ميدان درة، الشيخ زايد',
                'ar_hours'        => 'السبت – الخميس: 8:00 صباحاً – 11:00 مساءً | الجمعة: 10:00 صباحاً – 6:00 مساءً',
            ],
            [
                'slug'            => '6th-october',
                'phone'           => '01000000002',
                'whatsapp'        => '01000000002',
                'sort_order'      => 2,
                'google_maps_url' => 'https://maps.google.com/',
                'latitude'        => 29.9765,
                'longitude'       => 30.9256,
                'en_name'         => '6th of October Branch',
                'en_city'         => 'Giza',
                'en_address'      => 'Central Axis, 6th of October City, Giza',
                'en_hours'        => 'Sat–Thu: 8:00 AM – 11:00 PM | Fri: 10:00 AM – 6:00 PM',
                'ar_name'         => 'فرع السادس من أكتوبر',
                'ar_city'         => 'الجيزة',
                'ar_address'      => 'المحور المركزي، مدينة السادس من أكتوبر، الجيزة',
                'ar_hours'        => 'السبت – الخميس: 8:00 صباحاً – 11:00 مساءً | الجمعة: 10:00 صباحاً – 6:00 مساءً',
            ],
            [
                'slug'            => 'mohandessin',
                'phone'           => '01000000003',
                'whatsapp'        => '01000000003',
                'sort_order'      => 3,
                'google_maps_url' => 'https://maps.google.com/',
                'latitude'        => 30.0626,
                'longitude'       => 31.2006,
                'en_name'         => 'Mohandessin Branch',
                'en_city'         => 'Cairo',
                'en_address'      => 'Gamaet El Dowal El Arabeya St., Mohandessin, Cairo',
                'en_hours'        => 'Sat–Thu: 8:00 AM – 11:00 PM | Fri: 10:00 AM – 6:00 PM',
                'ar_name'         => 'فرع المهندسين',
                'ar_city'         => 'القاهرة',
                'ar_address'      => 'شارع جامعة الدول العربية، المهندسين، القاهرة',
                'ar_hours'        => 'السبت – الخميس: 8:00 صباحاً – 11:00 مساءً | الجمعة: 10:00 صباحاً – 6:00 مساءً',
            ],
            [
                'slug'            => 'nasr-city',
                'phone'           => '01000000004',
                'whatsapp'        => '01000000004',
                'sort_order'      => 4,
                'google_maps_url' => 'https://maps.google.com/',
                'latitude'        => 30.0638,
                'longitude'       => 31.3417,
                'en_name'         => 'Nasr City Branch',
                'en_city'         => 'Cairo',
                'en_address'      => 'Abbas El Akkad St., Nasr City, Cairo',
                'en_hours'        => 'Sat–Thu: 8:00 AM – 11:00 PM | Fri: 10:00 AM – 6:00 PM',
                'ar_name'         => 'فرع مدينة نصر',
                'ar_city'         => 'القاهرة',
                'ar_address'      => 'شارع عباس العقاد، مدينة نصر، القاهرة',
                'ar_hours'        => 'السبت – الخميس: 8:00 صباحاً – 11:00 مساءً | الجمعة: 10:00 صباحاً – 6:00 مساءً',
            ],
        ];

        foreach ($branches as $br) {
            $id = DB::table('branches')->insertGetId([
                'slug'            => $br['slug'],
                'is_active'       => true,
                'sort_order'      => $br['sort_order'],
                'phone'           => $br['phone'],
                'whatsapp'        => $br['whatsapp'],
                'google_maps_url' => $br['google_maps_url'],
                'latitude'        => $br['latitude'],
                'longitude'       => $br['longitude'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::table('branch_translations')->insert([
                [
                    'branch_id'    => $id,
                    'locale'       => 'en',
                    'name'         => $br['en_name'],
                    'city'         => $br['en_city'],
                    'address'      => $br['en_address'],
                    'working_hours'=> $br['en_hours'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ],
                [
                    'branch_id'    => $id,
                    'locale'       => 'ar',
                    'name'         => $br['ar_name'],
                    'city'         => $br['ar_city'],
                    'address'      => $br['ar_address'],
                    'working_hours'=> $br['ar_hours'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ],
            ]);
        }
    }
}
