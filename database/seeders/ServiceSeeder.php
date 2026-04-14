<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug'       => 'patient-services',
                'icon'       => 'heroicon-o-user',
                'color'      => 'green',
                'sort_order' => 1,
                'en_name'    => 'Patient Services',
                'en_short'   => 'Convenient, accurate, and fast lab services for individuals.',
                'en_desc'    => '<p>We put the patient at the center of everything we do. From easy online booking to fast result delivery, our patient services are designed for your comfort and peace of mind.</p>',
                'en_features'=> [
                    'Online and phone booking for in-lab or home collection',
                    'Results delivered by email within hours',
                    'Dedicated patient support team',
                    'Test preparation guides for every panel',
                    'Walk-in welcome at all branches',
                    'Secure online results portal',
                ],
                'ar_name'    => 'خدمات المرضى',
                'ar_short'   => 'خدمات معملية مريحة ودقيقة وسريعة للأفراد.',
                'ar_desc'    => '<p>نضع المريض في قلب كل ما نقوم به. من الحجز السهل عبر الإنترنت إلى استلام النتائج بسرعة، خدماتنا مصممة لراحتك وطمأنينتك.</p>',
                'ar_features'=> [
                    'حجز إلكتروني وهاتفي للزيارة أو خدمة المنزل',
                    'النتائج ترسل بالبريد الإلكتروني في غضون ساعات',
                    'فريق دعم مرضى متخصص',
                    'أدلة تحضير للتحاليل لكل فحص',
                    'الزيارة المباشرة مرحب بها في جميع الفروع',
                    'بوابة إلكترونية آمنة لعرض النتائج',
                ],
            ],
            [
                'slug'       => 'doctor-services',
                'icon'       => 'heroicon-o-academic-cap',
                'color'      => 'blue',
                'sort_order' => 2,
                'en_name'    => 'Doctor Services',
                'en_short'   => 'Partnering with physicians to deliver superior patient outcomes.',
                'en_desc'    => '<p>We partner with doctors across all specialties to provide reliable diagnostic support. Our physician portal gives you direct access to your patients\' results and the latest diagnostic literature.</p>',
                'en_features'=> [
                    'Physician portal for real-time patient results',
                    'Fast turnaround for urgent panels',
                    'Specialty-specific diagnostic guides (PDF)',
                    'Free consultation with our lab specialists',
                    'Dedicated account manager for medical offices',
                    'Regular clinical updates and newsletters',
                ],
                'ar_name'    => 'خدمات الأطباء',
                'ar_short'   => 'شراكة مع الأطباء لتحقيق أفضل نتائج للمرضى.',
                'ar_desc'    => '<p>نتشارك مع أطباء جميع التخصصات لتقديم دعم تشخيصي موثوق. بوابتنا الطبية تمنحك وصولاً فورياً لنتائج مرضاك وأحدث الأدلة التشخيصية.</p>',
                'ar_features'=> [
                    'بوابة إلكترونية للأطباء للاطلاع على نتائج المرضى فوراً',
                    'تسليم سريع للنتائج العاجلة',
                    'أدلة تشخيصية حسب التخصص (PDF)',
                    'استشارة مجانية مع متخصصي المعمل',
                    'مدير حساب مخصص للعيادات',
                    'تحديثات سريرية ونشرات إخبارية دورية',
                ],
            ],
            [
                'slug'       => 'corporate-services',
                'icon'       => 'heroicon-o-building-office',
                'color'      => 'orange',
                'sort_order' => 3,
                'en_name'    => 'Corporate Services',
                'en_short'   => 'Workplace health programs for healthier, more productive teams.',
                'en_desc'    => '<p>Invest in your team\'s health with our comprehensive corporate wellness programs. From pre-employment screening to executive health checks, we support businesses of all sizes.</p>',
                'en_features'=> [
                    'Pre-employment health screenings',
                    'Annual employee wellness programs',
                    'Drug testing services',
                    'On-site "Know Your Numbers" health events',
                    'Executive health check packages',
                    'Flexible invoicing and corporate accounts',
                ],
                'ar_name'    => 'خدمات الشركات',
                'ar_short'   => 'برامج صحة مكان العمل لفرق أكثر صحة وإنتاجية.',
                'ar_desc'    => '<p>استثمر في صحة فريقك ببرامج العافية المؤسسية الشاملة. من فحص ما قبل التعيين إلى الفحوصات التنفيذية، ندعم الشركات بكل أحجامها.</p>',
                'ar_features'=> [
                    'فحوصات صحية ما قبل التعيين',
                    'برامج صحة سنوية للموظفين',
                    'خدمات الكشف عن المخدرات',
                    'فعاليات صحية في مقر العمل "اعرف أرقامك"',
                    'باقات فحوصات صحية تنفيذية',
                    'فواتير مرنة وحسابات مؤسسية',
                ],
            ],
            [
                'slug'       => 'hospital-services',
                'icon'       => 'heroicon-o-building-library',
                'color'      => 'purple',
                'sort_order' => 4,
                'en_name'    => 'Hospital Services',
                'en_short'   => 'Full laboratory management and logistics support for healthcare facilities.',
                'en_desc'    => '<p>We partner with hospitals and healthcare facilities to manage laboratory operations, logistics, and IT integration — so their teams can focus on patient care.</p>',
                'en_features'=> [
                    'Full lab management and pathology support',
                    'Logistics and sample transport solutions',
                    'Lab IT system integration (LIS/HIS)',
                    'Workflow process improvement consulting',
                    'Quality control and accreditation support',
                    'Customized reporting and dashboards',
                ],
                'ar_name'    => 'خدمات المستشفيات',
                'ar_short'   => 'إدارة شاملة للمعامل ودعم اللوجستيات لمنشآت الرعاية الصحية.',
                'ar_desc'    => '<p>نتشارك مع المستشفيات ومنشآت الرعاية الصحية في إدارة العمليات المعملية واللوجستيات وتكامل أنظمة المعلومات — حتى يتفرغ فريقهم لرعاية المرضى.</p>',
                'ar_features'=> [
                    'إدارة معمل شاملة ودعم الأمراض',
                    'حلول لوجستية ونقل العينات',
                    'تكامل أنظمة معلومات المعمل (LIS/HIS)',
                    'استشارات تحسين العمليات',
                    'دعم ضبط الجودة والاعتماد',
                    'تقارير ولوحات معلومات مخصصة',
                ],
            ],
        ];

        foreach ($services as $svc) {
            $id = DB::table('services')->insertGetId([
                'slug'       => $svc['slug'],
                'icon'       => $svc['icon'],
                'color'      => $svc['color'],
                'is_active'  => true,
                'sort_order' => $svc['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('service_translations')->insert([
                [
                    'service_id'        => $id,
                    'locale'            => 'en',
                    'name'              => $svc['en_name'],
                    'short_description' => $svc['en_short'],
                    'description'       => $svc['en_desc'],
                    'features'          => json_encode($svc['en_features']),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
                [
                    'service_id'        => $id,
                    'locale'            => 'ar',
                    'name'              => $svc['ar_name'],
                    'short_description' => $svc['ar_short'],
                    'description'       => $svc['ar_desc'],
                    'features'          => json_encode($svc['ar_features']),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
            ]);
        }
    }
}
