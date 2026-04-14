<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Corrects wrong prices, updates descriptions to match AccuLab actual data,
 * and adds the 2 missing partners (WAFI Center, Cairo Capital Clinics).
 */
class FixAndCompleteSeeder extends Seeder
{
    public function run(): void
    {
        $this->fixPackagePrices();
        $this->fixPackageDescriptions();
        $this->addMissingPartners();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. Price corrections (prices were wrong vs AccuLab actual values)
    // ─────────────────────────────────────────────────────────────────────────
    private function fixPackagePrices(): void
    {
        $fixes = [
            // Thyroid Check: 500 EGP → 700 EGP  /  666 EGP → 933 EGP original
            'thyroid-check' => ['price' => 70000, 'original_price' => 93300],

            // Heart Check: 800 EGP → 1,800 EGP  /  1,066 EGP → 2,400 EGP original
            'heart-check'   => ['price' => 180000, 'original_price' => 240000],
        ];

        foreach ($fixes as $slug => $data) {
            DB::table('packages')
                ->where('slug', $slug)
                ->update(array_merge($data, ['updated_at' => now()]));
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. Description corrections (updated from AccuLab actual product pages)
    // ─────────────────────────────────────────────────────────────────────────
    private function fixPackageDescriptions(): void
    {
        $updates = [

            'heart-check' => [
                'en' => [
                    'name'              => 'Heart Check',
                    'short_description' => 'Comprehensive cardiovascular risk assessment including cardiac enzymes, lipid profile, and BNP.',
                    'description'       => '<p>The Heart Check is a comprehensive cardiovascular assessment that evaluates blood markers related to heart health. Recommended for anyone with shortness of breath on exertion, fatigue, swelling in the limbs, irregular heartbeat, or persistent cough.</p><ul><li>Cardiac enzymes: CK-MB and Troponin (heart attack detection)</li><li>Lipid profile: total cholesterol, LDL, HDL, and triglycerides</li><li>BNP (brain natriuretic peptide) — screens for heart failure</li><li>Complete Blood Count (CBC)</li><li>Thyroid function (TSH, T3, T4) — thyroid affects heart rate</li><li>Blood glucose & HbA1c</li></ul>',
                ],
                'ar' => [
                    'name'              => 'فحص صحة القلب',
                    'short_description' => 'تقييم شامل لأمراض القلب يشمل إنزيمات القلب ودهون الدم وBNP.',
                    'description'       => '<p>فحص القلب هو تقييم قلبي وعائي شامل يقيس المؤشرات الحيوية المرتبطة بصحة القلب. موصى به لكل من يعاني من ضيق في التنفس عند المجهود، أو تعب، أو تورم في الأطراف، أو خفقان، أو سعال مستمر.</p><ul><li>إنزيمات القلب: CK-MB والتروبونين (الكشف عن النوبة القلبية)</li><li>دهون الدم: الكوليسترول الكلي، LDL، HDL، والثلاثيات</li><li>BNP (الببتيد الناتريوريتيكي) — للكشف عن قصور القلب</li><li>صورة دم كاملة (CBC)</li><li>وظائف الغدة الدرقية (TSH، T3، T4) — الدرقية تؤثر على معدل القلب</li><li>سكر الدم وHbA1c</li></ul>',
                ],
            ],

            'std-check-9' => [
                'en' => [
                    'name'              => 'STD Check 9',
                    'short_description' => 'Confidential PCR screening for 9 sexually transmitted infections including HPV and Chlamydia.',
                    'description'       => '<p>The STD Check 9 uses Multiplex PCR technology to simultaneously detect 9 sexually transmitted infections from a single sample — the most sensitive and accurate method available. Suitable for skin lesion swabs, genital/urethral secretions, cervical swabs, or first-void morning urine.</p><ul><li>Herpes Simplex Virus type 1 &amp; 2 (PCR)</li><li>Human Papillomavirus (HPV)</li><li>Neisseria Gonorrhoeae (Gonorrhoea)</li><li>Chlamydia Trachomatis</li><li>Mycoplasma Hominis</li><li>Ureaplasma Urealyticum</li><li>Treponema Pallidum (Syphilis)</li><li>+ 2 additional STI markers</li></ul>',
                ],
                'ar' => [
                    'name'              => 'فحص الأمراض المنقولة جنسياً – 9 أمراض',
                    'short_description' => 'فحص PCR سري لـ 9 أمراض منقولة جنسياً يشمل HPV والكلاميديا.',
                    'description'       => '<p>يستخدم فحص STD Check 9 تقنية PCR المتعدد للكشف عن 9 أمراض منقولة جنسياً من عينة واحدة — الطريقة الأكثر دقة وحساسية المتاحة. مناسب للمسحات الجلدية، إفرازات الأعضاء التناسلية، مسحات عنق الرحم، أو أول بول صباحي.</p><ul><li>فيروس الهربس البسيط النوع 1 و2 (PCR)</li><li>فيروس الورم الحليمي البشري (HPV)</li><li>المكورات البنية (السيلان)</li><li>الكلاميديا تراكوماتيس</li><li>الميكوبلازما هومينيس</li><li>اليوريابلازما يوريالتيكوم</li><li>الليلبية الشاحبة (الزهري)</li><li>+ مؤشران إضافيان للأمراض المنقولة جنسياً</li></ul>',
                ],
            ],

            'cancer-check-female' => [
                'en' => [
                    'name'              => 'Cancer Check – Female',
                    'short_description' => 'Female cancer tumor markers panel for early detection including CRP and ESR.',
                    'description'       => '<p>The Cancer Check Female is designed to find cancer or pre-cancerous changes before symptoms appear — when treatment is most effective. Uses 8 key tumor markers and inflammatory indicators.</p><ul><li>Alpha Feto Protein (AFP) — liver cancer</li><li>CA 15-3 — breast cancer</li><li>CA 125 — ovarian cancer</li><li>CA 19-9 — pancreatic &amp; GI cancers</li><li>Carcinoembryonic Antigen (CEA) — bowel &amp; lung cancers</li><li>Complete Blood Count (CBC)</li><li>C-Reactive Protein (CRP) — quantitative</li><li>Erythrocyte Sedimentation Rate (ESR)</li></ul>',
                ],
                'ar' => [
                    'name'              => 'فحص السرطان – نساء',
                    'short_description' => 'فحص علامات الأورام للمرأة يشمل CRP وESR للكشف المبكر.',
                    'description'       => '<p>فحص السرطان للمرأة مصمم للكشف عن السرطان أو التغيرات السابقة قبل ظهور الأعراض — عندما يكون العلاج أكثر فاعلية. يشمل 8 علامات ورمية ومؤشرات التهاب رئيسية.</p><ul><li>بروتين ألفا فيتو (AFP) — سرطان الكبد</li><li>CA 15-3 — سرطان الثدي</li><li>CA 125 — سرطان المبيض</li><li>CA 19-9 — سرطان البنكرياس والجهاز الهضمي</li><li>المستضد السرطاني الجنيني (CEA) — سرطان القولون والرئة</li><li>صورة دم كاملة (CBC)</li><li>بروتين سي التفاعلي (CRP) — كمي</li><li>سرعة ترسيب كرات الدم الحمراء (ESR)</li></ul>',
                ],
            ],

            'chronic-fatigue-panel' => [
                'en' => [
                    'name'              => 'Chronic Fatigue Panel',
                    'short_description' => 'Find out why you\'re always tired — 13 tests including EBV, CMV, cortisol, and hormones.',
                    'description'       => '<p>Persistent fatigue can have many causes. The Chronic Fatigue Panel targets both infectious and hormonal triggers in a single draw of 13 tests — helping identify the root cause of your exhaustion.</p><ul><li>Complete Blood Count (CBC)</li><li>Epstein-Barr Virus Antibodies IgG &amp; IgM (EBV)</li><li>Cytomegalovirus Antibodies IgG &amp; IgM (CMV)</li><li>Ferritin (iron stores)</li><li>Total &amp; Free Testosterone</li><li>DHEA-S</li><li>Free T3 &amp; Free T4 (thyroid)</li><li>Cortisol (stress hormone)</li><li>hs-CRP (high-sensitivity C-Reactive Protein)</li><li>Vitamin B12</li><li>Folate</li><li>Insulin</li></ul>',
                ],
                'ar' => [
                    'name'              => 'فحص الإرهاق المزمن',
                    'short_description' => 'اكتشف سبب تعبك الدائم — 13 تحليلاً يشمل EBV وCMV والكورتيزول والهرمونات.',
                    'description'       => '<p>قد يكون للتعب المستمر أسباب متعددة. يستهدف فحص الإرهاق المزمن المحفزات المعدية والهرمونية معاً في 13 تحليلاً من عينة واحدة — لتحديد السبب الجذري لإرهاقك.</p><ul><li>صورة دم كاملة (CBC)</li><li>أجسام مضادة لفيروس إبشتاين بار IgG وIgM (EBV)</li><li>أجسام مضادة للفيروس المضخم للخلايا IgG وIgM (CMV)</li><li>فيريتين (مخزون الحديد)</li><li>تيستوستيرون كلي وحر</li><li>DHEA-S</li><li>T3 الحر وT4 الحر (الغدة الدرقية)</li><li>كورتيزول (هرمون الإجهاد)</li><li>hs-CRP (بروتين سي التفاعلي عالي الحساسية)</li><li>فيتامين ب12</li><li>حمض الفوليك</li><li>إنسولين</li></ul>',
                ],
            ],

            'acne-panel' => [
                'en' => [
                    'name'              => 'Acne Panel',
                    'short_description' => 'Hormonal causes of acne — tests for PCOS, hyperandrogenism, and prolactin.',
                    'description'       => '<p>The Acne Panel is recommended for patients showing signs of hormonal imbalances causing acne. Helps identify: hyperandrogenism (elevated male hormones), polycystic ovary syndrome (PCOS), excess prolactin, or Cushing syndrome.</p><ul><li>Complete Blood Count (CBC)</li><li>Blood glucose &amp; insulin</li><li>DHEA-S</li><li>Testosterone (free &amp; total)</li><li>LH &amp; FSH (ovarian hormones)</li><li>Prolactin</li><li>Zinc levels</li></ul>',
                ],
                'ar' => [
                    'name'              => 'فحص حب الشباب',
                    'short_description' => 'الأسباب الهرمونية لحب الشباب — فحص متلازمة تكيس المبايض والأندروجينات والبروليكتين.',
                    'description'       => '<p>فحص حب الشباب موصى به للمرضى الذين يظهرون علامات الاختلالات الهرمونية المسببة للبثور. يساعد في تحديد: فرط الأندروجينات، متلازمة تكيس المبايض، ارتفاع البروليكتين، أو متلازمة كوشينج.</p><ul><li>صورة دم كاملة (CBC)</li><li>سكر الدم والإنسولين</li><li>DHEA-S</li><li>تيستوستيرون (كلي وحر)</li><li>LH وFSH (هرمونات المبيض)</li><li>بروليكتين</li><li>مستوى الزنك</li></ul>',
                ],
            ],

            'thyroid-check' => [
                'en' => [
                    'name'              => 'Thyroid Check',
                    'short_description' => 'Complete thyroid function panel — TSH, Free T3, Free T4, and autoantibodies.',
                    'description'       => '<p>The Thyroid Check evaluates gland function and diagnoses thyroid disorders by measuring hormone and antibody levels. Recommended for anyone with symptoms of an underactive thyroid (fatigue, weight gain, dry skin, hair loss, constipation, depression) or overactive thyroid (rapid heart rate, anxiety, weight loss, sweating, tremors).</p><ul><li>TSH (Thyroid Stimulating Hormone)</li><li>Free T3</li><li>Free T4</li><li>Thyroid autoantibodies (anti-TPO &amp; anti-Thyroglobulin)</li></ul>',
                ],
                'ar' => [
                    'name'              => 'فحص الغدة الدرقية',
                    'short_description' => 'فحص شامل لوظائف الغدة الدرقية — TSH وT3 وT4 والأجسام المضادة.',
                    'description'       => '<p>يقيّم فحص الغدة الدرقية وظيفة الغدة ويشخّص اضطراباتها بقياس مستويات الهرمونات والأجسام المضادة. موصى به لمن يعاني من أعراض خمول الغدة (تعب، زيادة وزن، جفاف جلد، تساقط شعر، إمساك، اكتئاب) أو نشاط مفرط (خفقان، قلق، نقص وزن، تعرق، رعشة).</p><ul><li>TSH (الهرمون المنشط للغدة الدرقية)</li><li>T3 الحر</li><li>T4 الحر</li><li>أجسام مضادة للغدة الدرقية (Anti-TPO وAnti-Thyroglobulin)</li></ul>',
                ],
            ],

        ];

        foreach ($updates as $slug => $locales) {
            $pkg = DB::table('packages')->where('slug', $slug)->first();
            if (! $pkg) {
                continue;
            }
            foreach ($locales as $locale => $data) {
                DB::table('package_translations')
                    ->where('package_id', $pkg->id)
                    ->where('locale', $locale)
                    ->update(array_merge($data, ['updated_at' => now()]));
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 3. Add 2 missing partners (WAFI Center & Cairo Capital Clinics)
    // ─────────────────────────────────────────────────────────────────────────
    private function addMissingPartners(): void
    {
        $partners = [
            [
                'slug'        => 'wafi-center',
                'website_url' => null,
                'phone'       => null,
                'sort_order'  => 6,
                'en_name'     => 'WAFI Center',
                'en_spec'     => 'Breast Cancer Screening & Fetal Imaging',
                'en_desc'     => 'Women and Fetal Imaging (WAFI) Center offers a holistic approach to breast cancer screening, diagnostic breast imaging, and fetal imaging. Services include basic tests, genetic testing, BRCA testing, and NIPT.',
                'ar_name'     => 'مركز وافي',
                'ar_spec'     => 'فحص سرطان الثدي وتصوير الجنين',
                'ar_desc'     => 'يقدم مركز المرأة والجنين (وافي) نهجاً شاملاً للكشف عن سرطان الثدي والتصوير التشخيصي للثدي وتصوير الجنين. تشمل الخدمات الفحوصات الأساسية، الاختبارات الجينية، اختبار BRCA، وNIPT.',
            ],
            [
                'slug'        => 'cairo-capital-clinics',
                'website_url' => null,
                'phone'       => null,
                'sort_order'  => 7,
                'en_name'     => 'Cairo Capital Clinics',
                'en_spec'     => 'Personalized Healthcare Services',
                'en_desc'     => 'Based in Zamalek, Cairo Capital Clinics delivers personalized laboratory services with the latest technologies and experienced healthcare professionals.',
                'ar_name'     => 'عيادات كايرو كابيتال',
                'ar_spec'     => 'خدمات رعاية صحية مخصصة',
                'ar_desc'     => 'تقع في الزمالك، وتقدم عيادات كايرو كابيتال خدمات معملية مخصصة بأحدث التقنيات وفريق طبي متخصص ذو خبرة.',
            ],
        ];

        foreach ($partners as $p) {
            if (DB::table('partners')->where('slug', $p['slug'])->exists()) {
                continue;
            }

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
