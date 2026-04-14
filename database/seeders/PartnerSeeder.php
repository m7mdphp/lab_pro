<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'slug'        => 'rofayda-hospital',
                'website_url' => null,
                'phone'       => null,
                'sort_order'  => 1,
                'en_name'     => 'Rofayda Hospital',
                'en_spec'     => 'Women & Child Health',
                'en_desc'     => 'Specialized in women and child health, prenatal testing, and NIPT services. Located in Sheikh Zayed.',
                'ar_name'     => 'مستشفى روفايدة',
                'ar_spec'     => 'صحة المرأة والطفل',
                'ar_desc'     => 'متخصص في صحة المرأة والطفل، الرعاية السابقة للولادة، وخدمات NIPT. يقع في الشيخ زايد.',
            ],
            [
                'slug'        => 'city-clinic',
                'website_url' => null,
                'phone'       => null,
                'sort_order'  => 2,
                'en_name'     => 'City Clinic',
                'en_spec'     => 'General Medicine & Specialty Clinics',
                'en_desc'     => 'A major polyclinic chain offering diverse medical specialties across Cairo.',
                'ar_name'     => 'سيتي كلينيك',
                'ar_spec'     => 'طب عام وعيادات متخصصة',
                'ar_desc'     => 'سلسلة مجمعات طبية كبرى تقدم تخصصات طبية متنوعة في أنحاء القاهرة.',
            ],
            [
                'slug'        => 'dawi-clinics',
                'website_url' => null,
                'phone'       => null,
                'sort_order'  => 3,
                'en_name'     => 'Dawi Clinics',
                'en_spec'     => 'Multi-specialty Medical Clinics',
                'en_desc'     => 'One of the largest medical clinic chains in Egypt with multiple locations in Cairo.',
                'ar_name'     => 'داوي كلينيكس',
                'ar_spec'     => 'عيادات طبية متعددة التخصصات',
                'ar_desc'     => 'من أكبر سلاسل العيادات الطبية في مصر بمواقع متعددة في القاهرة.',
            ],
            [
                'slug'        => 'el-zohour-hospital',
                'website_url' => null,
                'phone'       => null,
                'sort_order'  => 4,
                'en_name'     => 'El Zohour Hospital',
                'en_spec'     => 'Cardiology & Surgery',
                'en_desc'     => 'Specialized in cardiology and surgical services. Lab partnership covers cardiac surgery support.',
                'ar_name'     => 'مستشفى الزهور',
                'ar_spec'     => 'القلب والجراحة',
                'ar_desc'     => 'متخصص في أمراض القلب والجراحة. تشمل الشراكة المعملية دعم عمليات قلب مفتوح.',
            ],
            [
                'slug'        => 'sama-clinics',
                'website_url' => null,
                'phone'       => null,
                'sort_order'  => 5,
                'en_name'     => 'Sama Clinics',
                'en_spec'     => 'Multi-specialty Clinics',
                'en_desc'     => 'Multi-specialty medical clinics located in 6th of October City and El Sheikh Zayed.',
                'ar_name'     => 'سما كلينيكس',
                'ar_spec'     => 'عيادات متعددة التخصصات',
                'ar_desc'     => 'عيادات طبية متعددة التخصصات تقع في مدينة السادس من أكتوبر والشيخ زايد.',
            ],
        ];

        foreach ($partners as $p) {
            $id = DB::table('partners')->insertGetId([
                'slug'        => $p['slug'],
                'is_active'   => true,
                'sort_order'  => $p['sort_order'],
                'website_url' => $p['website_url'],
                'phone'       => $p['phone'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            DB::table('partner_translations')->insert([
                [
                    'partner_id'  => $id,
                    'locale'      => 'en',
                    'name'        => $p['en_name'],
                    'specialty'   => $p['en_spec'],
                    'description' => $p['en_desc'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'partner_id'  => $id,
                    'locale'      => 'ar',
                    'name'        => $p['ar_name'],
                    'specialty'   => $p['ar_spec'],
                    'description' => $p['ar_desc'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }
    }
}
