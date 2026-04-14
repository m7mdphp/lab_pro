<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompleteDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMissingCategories();
        $this->seedMissingPackages();
    }

    private function seedMissingCategories(): void
    {
        $categories = [
            ['slug' => 'energy',        'sort_order' => 16, 'en' => 'Energy & Vitality',    'ar' => 'الطاقة والحيوية'],
            ['slug' => 'sexual-health', 'sort_order' => 17, 'en' => 'Sexual Health',         'ar' => 'الصحة الجنسية'],
            ['slug' => 'kits',          'sort_order' => 18, 'en' => 'Home Test Kits',         'ar' => 'المجموعات المنزلية'],
        ];

        foreach ($categories as $cat) {
            if (DB::table('test_categories')->where('slug', $cat['slug'])->exists()) {
                continue;
            }

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

    private function seedMissingPackages(): void
    {
        // price stored as piastres (EGP × 100)
        $packages = [
            // ─── Men's Health ───────────────────────────────────────────────
            [
                'slug'           => 'well-man-check-plus',
                'price'          => 330000,
                'original_price' => 440000,
                'is_featured'    => true,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 21,
                'categories'     => ['mens-health'],
                'en_name'        => 'Well Man Check Plus',
                'en_short'       => 'Advanced men\'s health panel with PSA, testosterone, and vitamin D.',
                'en_desc'        => '<p>Everything in Well Man Check plus vitamin D, vitamin B12, testosterone (free & total), PSA, and SHBG — the complete picture of men\'s health.</p>',
                'ar_name'        => 'فحص الرجل الشامل المتقدم',
                'ar_short'       => 'فحص متقدم للرجل يشمل PSA والتيستوستيرون وفيتامين د.',
                'ar_desc'        => '<p>كل ما في فحص الرجل الشامل مع إضافة فيتامين د، ب12، تيستوستيرون (كلي وحر)، PSA، وSHBG — صورة متكاملة لصحة الرجل.</p>',
            ],

            // ─── Allergy ────────────────────────────────────────────────────
            [
                'slug'           => 'inhalant-panel-30',
                'price'          => 85000,
                'original_price' => 113300,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 22,
                'categories'     => ['allergy'],
                'en_name'        => 'Inhalant Allergy Panel 30',
                'en_short'       => 'IgE testing for 30 common airborne allergens (dust, pollen, mold, pet dander).',
                'en_desc'        => '<p>Tests for IgE antibodies to 30 common inhalant allergens including house dust mites, grass pollens, tree pollens, mold spores, cat and dog dander.</p>',
                'ar_name'        => 'فحص حساسية الاستنشاق 30',
                'ar_short'       => 'قياس IgE لـ 30 مسبباً للحساسية عبر الهواء.',
                'ar_desc'        => '<p>يقيس أجسام IgE المضادة لـ 30 نوعاً من مسببات الحساسية عبر الهواء كعث الغبار، حبوب اللقاح، العفن، وشعر الحيوانات الأليفة.</p>',
            ],
            [
                'slug'           => 'imupro-22',
                'price'          => 200000,
                'original_price' => 266600,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 23,
                'categories'     => ['allergy'],
                'en_name'        => 'ImuPro 22 – Food Intolerance',
                'en_short'       => 'IgG-based food intolerance test for 22 food groups.',
                'en_desc'        => '<p>ImuPro tests for IgG4 antibodies to 22 food items to identify delayed food intolerances that may cause chronic symptoms like bloating, fatigue, and skin problems.</p>',
                'ar_name'        => 'ImuPro 22 – حساسية الطعام المتأخرة',
                'ar_short'       => 'فحص IgG لعدم تحمل الطعام لـ 22 مجموعة غذائية.',
                'ar_desc'        => '<p>يفحص ImuPro أجسام IgG4 المضادة لـ 22 نوعاً من الأطعمة لتحديد تحسسيات الطعام المتأخرة المسببة للانتفاخ والتعب وأمراض الجلد.</p>',
            ],
            [
                'slug'           => 'imupro-44',
                'price'          => 280000,
                'original_price' => 373300,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 24,
                'categories'     => ['allergy'],
                'en_name'        => 'ImuPro 44 – Food Intolerance',
                'en_short'       => 'IgG-based food intolerance test for 44 food groups.',
                'en_desc'        => '<p>Expanded ImuPro panel testing IgG4 antibodies to 44 food items — ideal for identifying a broader range of delayed food intolerances.</p>',
                'ar_name'        => 'ImuPro 44 – حساسية الطعام المتأخرة',
                'ar_short'       => 'فحص IgG لعدم تحمل الطعام لـ 44 مجموعة غذائية.',
                'ar_desc'        => '<p>لوحة ImuPro الموسعة تختبر أجسام IgG4 لـ 44 نوعاً من الأطعمة — مثالية لتحديد مجموعة أوسع من تحسسيات الطعام المتأخرة.</p>',
            ],
            [
                'slug'           => 'imupro-90',
                'price'          => 450000,
                'original_price' => 600000,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 25,
                'categories'     => ['allergy'],
                'en_name'        => 'ImuPro 90 – Food Intolerance',
                'en_short'       => 'IgG-based food intolerance test for 90 food items.',
                'en_desc'        => '<p>Comprehensive ImuPro panel testing IgG4 antibodies to 90 food items — the most thorough delayed food intolerance assessment available.</p>',
                'ar_name'        => 'ImuPro 90 – حساسية الطعام المتأخرة',
                'ar_short'       => 'فحص IgG لعدم تحمل الطعام لـ 90 نوعاً من الأطعمة.',
                'ar_desc'        => '<p>لوحة ImuPro الشاملة تختبر أجسام IgG4 لـ 90 نوعاً من الأطعمة — الأكثر دقة وشمولاً في تقييم تحسسيات الطعام المتأخرة.</p>',
            ],
            [
                'slug'           => 'imupro-270',
                'price'          => 780000,
                'original_price' => 1040000,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 26,
                'categories'     => ['allergy'],
                'en_name'        => 'ImuPro 270 – Food Intolerance',
                'en_short'       => 'The most comprehensive IgG food intolerance test — 270 foods.',
                'en_desc'        => '<p>ImuPro 270 is the gold standard for delayed food intolerance testing, covering IgG4 antibodies to 270 food items across all major food groups.</p>',
                'ar_name'        => 'ImuPro 270 – حساسية الطعام المتأخرة',
                'ar_short'       => 'الأشمل في فحص IgG لعدم تحمل الطعام — 270 نوعاً.',
                'ar_desc'        => '<p>ImuPro 270 هو المعيار الذهبي لفحص تحسسيات الطعام المتأخرة، يغطي أجسام IgG4 لـ 270 نوعاً من الأطعمة عبر جميع المجموعات الغذائية الرئيسية.</p>',
            ],

            // ─── Cancer ─────────────────────────────────────────────────────
            [
                'slug'           => 'colon-cancer-screening',
                'price'          => 170000,
                'original_price' => 226600,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood,stool',
                'sort_order'     => 27,
                'categories'     => ['cancer'],
                'en_name'        => 'Colon Cancer Screening',
                'en_short'       => 'Early detection panel for colorectal cancer risk.',
                'en_desc'        => '<p>CEA, CA 19-9, fecal occult blood test (FOBT), CBC, iron studies, and LDH — a comprehensive colorectal cancer risk assessment.</p>',
                'ar_name'        => 'فحص سرطان القولون',
                'ar_short'       => 'فحص الكشف المبكر عن خطر سرطان القولون والمستقيم.',
                'ar_desc'        => '<p>CEA، CA 19-9، الدم الخفي في البراز، صورة دم كاملة، مخازن الحديد، وLDH — تقييم شامل لخطر سرطان القولون والمستقيم.</p>',
            ],
            [
                'slug'           => 'cancer-check-male',
                'price'          => 160000,
                'original_price' => 213300,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 28,
                'categories'     => ['cancer', 'mens-health'],
                'en_name'        => 'Cancer Check – Male',
                'en_short'       => 'Male cancer tumor markers panel for early detection.',
                'en_desc'        => '<p>PSA (prostate), AFP (liver), CEA (bowel), CA 19-9 (pancreas/GI), and CBC — comprehensive early cancer detection for men.</p>',
                'ar_name'        => 'فحص السرطان – رجال',
                'ar_short'       => 'فحص علامات الأورام للرجل للكشف المبكر.',
                'ar_desc'        => '<p>PSA (البروستاتا)، AFP (الكبد)، CEA (القولون)، CA 19-9 (البنكرياس والجهاز الهضمي)، وصورة دم كاملة — كشف مبكر شامل عن السرطان للرجال.</p>',
            ],

            // ─── Energy & Vitality ──────────────────────────────────────────
            [
                'slug'           => 'chronic-fatigue-panel',
                'price'          => 158000,
                'original_price' => 210600,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 29,
                'categories'     => ['energy', 'general-wellness'],
                'en_name'        => 'Chronic Fatigue Panel',
                'en_short'       => 'Find out why you\'re always tired with this comprehensive fatigue screen.',
                'en_desc'        => '<p>CBC, iron & ferritin, vitamin D, vitamin B12, folate, TSH, T3, T4, cortisol, blood sugar, liver & kidney function, and magnesium.</p>',
                'ar_name'        => 'فحص الإرهاق المزمن',
                'ar_short'       => 'اكتشف سبب التعب الدائم بهذا الفحص الشامل.',
                'ar_desc'        => '<p>صورة دم كاملة، الحديد والفيريتين، فيتامين د، ب12، حمض الفوليك، TSH، T3، T4، كورتيزول، سكر الدم، وظائف الكبد والكلى، والمغنيسيوم.</p>',
            ],
            [
                'slug'           => 'rheumatology-panel',
                'price'          => 115000,
                'original_price' => 153300,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 30,
                'categories'     => ['energy', 'bone-joint'],
                'en_name'        => 'Rheumatology Panel',
                'en_short'       => 'Comprehensive screen for autoimmune and rheumatic conditions.',
                'en_desc'        => '<p>ESR, CRP, hs-CRP, RF (rheumatoid factor), ANA, anti-dsDNA, uric acid, CBC, and complement (C3, C4).</p>',
                'ar_name'        => 'فحص الروماتيزم',
                'ar_short'       => 'فحص شامل للأمراض الروماتيزمية والمناعة الذاتية.',
                'ar_desc'        => '<p>سرعة الترسيب، CRP، hs-CRP، عامل الروماتويد، ANA، anti-dsDNA، حمض اليوريك، صورة دم كاملة، والمتمم (C3، C4).</p>',
            ],

            // ─── Gut Health ─────────────────────────────────────────────────
            [
                'slug'           => 'stomach-check-plus',
                'price'          => 280000,
                'original_price' => 373300,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood,stool',
                'sort_order'     => 31,
                'categories'     => ['gut-health'],
                'en_name'        => 'Stomach Check Plus',
                'en_short'       => 'Advanced gut health panel including H. pylori, celiac, and inflammatory markers.',
                'en_desc'        => '<p>H. pylori antibody, anti-tTG (celiac), calprotectin, liver enzymes, CBC, CRP, and stool analysis — a full digestive system assessment.</p>',
                'ar_name'        => 'فحص المعدة المتقدم',
                'ar_short'       => 'فحص متقدم لصحة الجهاز الهضمي يشمل H. pylori والسيلياك ومؤشرات الالتهاب.',
                'ar_desc'        => '<p>أجسام مضادة H. pylori، anti-tTG (السيلياك)، كالبروتكتين، إنزيمات الكبد، صورة دم، CRP، وتحليل البراز — تقييم شامل للجهاز الهضمي.</p>',
            ],

            // ─── Nutrition ──────────────────────────────────────────────────
            [
                'slug'           => 'dna-diet',
                'price'          => 600000,
                'original_price' => 800000,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'swab',
                'sort_order'     => 32,
                'categories'     => ['nutrition'],
                'en_name'        => 'DNA Diet',
                'en_short'       => 'Genetic test that reveals the ideal diet type for your DNA.',
                'en_desc'        => '<p>DNA Diet analyzes key genetic variants that influence how your body responds to different food types and macronutrients — enabling a truly personalized nutrition plan.</p>',
                'ar_name'        => 'DNA Diet – نظامك الغذائي من جيناتك',
                'ar_short'       => 'فحص جيني يكشف عن النظام الغذائي المثالي لجيناتك.',
                'ar_desc'        => '<p>يحلل DNA Diet المتغيرات الجينية الرئيسية التي تؤثر على استجابة جسمك لأنواع الطعام المختلفة والمغذيات الكبرى — مما يتيح خطة تغذية مخصصة تماماً لك.</p>',
            ],
            [
                'slug'           => 'pre-diet-check',
                'price'          => 65000,
                'original_price' => 86600,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 33,
                'categories'     => ['nutrition', 'general-wellness'],
                'en_name'        => 'Pre-Diet Check',
                'en_short'       => 'Essential lab tests before starting any weight management programme.',
                'en_desc'        => '<p>Fasting blood sugar, insulin, HOMA-IR, lipid profile, TSH, CBC, liver enzymes, and kidney function — know your baseline before you diet.</p>',
                'ar_name'        => 'فحص ما قبل الحمية',
                'ar_short'       => 'الفحوصات الأساسية قبل البدء في أي برنامج لإدارة الوزن.',
                'ar_desc'        => '<p>سكر الدم الصائم، إنسولين، HOMA-IR، دهون الدم، TSH، صورة دم، إنزيمات الكبد، ووظائف الكلى — اعرف نقطة بدايتك قبل الحمية.</p>',
            ],

            // ─── Sexual Health ──────────────────────────────────────────────
            [
                'slug'           => 'std-check-9',
                'price'          => 300000,
                'original_price' => 400000,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood,urine',
                'sort_order'     => 34,
                'categories'     => ['sexual-health'],
                'en_name'        => 'STD Check 9',
                'en_short'       => 'Confidential screening for 9 sexually transmitted infections.',
                'en_desc'        => '<p>HIV 1&2, hepatitis B (HBsAg), hepatitis C, syphilis (VDRL + TPHA), chlamydia, gonorrhoea, herpes simplex (HSV-1 & HSV-2), and trichomonas.</p>',
                'ar_name'        => 'فحص الأمراض المنقولة جنسياً – 9 أمراض',
                'ar_short'       => 'فحص سري لـ 9 أمراض منقولة جنسياً.',
                'ar_desc'        => '<p>HIV 1و2، التهاب الكبد B (HBsAg)، التهاب الكبد C، الزهري (VDRL وTPHA)، الكلاميديا، السيلان، الهربس (HSV-1 وHSV-2)، والتريكوموناس.</p>',
            ],

            // ─── Sports ─────────────────────────────────────────────────────
            [
                'slug'           => 'dna-sport',
                'price'          => 600000,
                'original_price' => 800000,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'swab',
                'sort_order'     => 35,
                'categories'     => ['sports'],
                'en_name'        => 'DNA Sport',
                'en_short'       => 'Genetic profile revealing your ideal sport type and injury risk.',
                'en_desc'        => '<p>DNA Sport analyzes genetic variants affecting endurance, power, recovery, and injury susceptibility — giving you a personalized athletic roadmap.</p>',
                'ar_name'        => 'DNA Sport – أداؤك الرياضي من جيناتك',
                'ar_short'       => 'ملف جيني يكشف عن نوع الرياضة المثالي لك ومخاطر الإصابة.',
                'ar_desc'        => '<p>يحلل DNA Sport المتغيرات الجينية المؤثرة على التحمل والقوة والتعافي وقابلية الإصابة — مما يتيح خارطة طريق رياضية مخصصة لك.</p>',
            ],

            // ─── General Wellness ────────────────────────────────────────────
            [
                'slug'           => 'bone-pain-panel',
                'price'          => 120000,
                'original_price' => 160000,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 36,
                'categories'     => ['bone-joint', 'general-wellness'],
                'en_name'        => 'Bone Pain Panel',
                'en_short'       => 'Comprehensive bone health and joint pain assessment.',
                'en_desc'        => '<p>Calcium, phosphorus, vitamin D (25-OH), PTH, alkaline phosphatase, uric acid, ESR, CRP, and RF — covers both metabolic and inflammatory causes of bone and joint pain.</p>',
                'ar_name'        => 'فحص آلام العظام',
                'ar_short'       => 'تقييم شامل لصحة العظام وآلام المفاصل.',
                'ar_desc'        => '<p>كالسيوم، فوسفور، فيتامين د، PTH، فوسفاتاز قلوي، حمض اليوريك، سرعة الترسيب، CRP، وعامل الروماتويد — يغطي الأسباب الأيضية والالتهابية لآلام العظام والمفاصل.</p>',
            ],
            [
                'slug'           => 'kidney-check',
                'price'          => 45000,
                'original_price' => 60000,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood,urine',
                'sort_order'     => 37,
                'categories'     => ['general-wellness'],
                'en_name'        => 'Kidney Check',
                'en_short'       => 'Essential kidney function test with urine analysis.',
                'en_desc'        => '<p>Creatinine, BUN (blood urea nitrogen), eGFR, uric acid, electrolytes (sodium, potassium), and complete urinalysis.</p>',
                'ar_name'        => 'فحص الكلى',
                'ar_short'       => 'فحص أساسي لوظائف الكلى مع تحليل البول.',
                'ar_desc'        => '<p>كرياتينين، يوريا الدم، eGFR، حمض اليوريك، إلكتروليتات (صوديوم، بوتاسيوم)، وتحليل بول كامل.</p>',
            ],
            [
                'slug'           => 'liver-check',
                'price'          => 50000,
                'original_price' => 66600,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 38,
                'categories'     => ['general-wellness'],
                'en_name'        => 'Liver Check',
                'en_short'       => 'Complete liver function assessment panel.',
                'en_desc'        => '<p>ALT, AST, ALP, GGT, albumin, bilirubin (total & direct), prothrombin time (PT), and hepatitis B & C screening.</p>',
                'ar_name'        => 'فحص الكبد',
                'ar_short'       => 'فحص شامل لوظائف الكبد.',
                'ar_desc'        => '<p>ALT، AST، ALP، GGT، ألبومين، بيليروبين (كلي ومباشر)، وقت البروثرومبين (PT)، وفحص التهاب الكبد B وC.</p>',
            ],
            [
                'slug'           => 'nannies-check',
                'price'          => 110000,
                'original_price' => 146600,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 39,
                'categories'     => ['general-wellness'],
                'en_name'        => 'Nannies & Domestic Workers Check',
                'en_short'       => 'Health screening panel for domestic workers and caregivers.',
                'en_desc'        => '<p>CBC, hepatitis B & C, HIV, syphilis, typhoid, stool analysis, urine analysis, and chest X-ray referral — all required for domestic worker health clearance.</p>',
                'ar_name'        => 'فحص العمالة المنزلية',
                'ar_short'       => 'فحوصات صحية للعمالة المنزلية ومقدمي الرعاية.',
                'ar_desc'        => '<p>صورة دم كاملة، التهاب كبد B وC، HIV، الزهري، التيفويد، تحليل البراز، تحليل البول، وتحويل للأشعة على الصدر — جميع الفحوصات المطلوبة للتصريح الصحي للعمالة المنزلية.</p>',
            ],
            [
                'slug'           => 'pre-hiring-check',
                'price'          => 110000,
                'original_price' => 146600,
                'is_featured'    => false,
                'is_kit'         => false,
                'sample_type'    => 'blood',
                'sort_order'     => 40,
                'categories'     => ['general-wellness'],
                'en_name'        => 'Pre-Employment Check',
                'en_short'       => 'Occupational health screening required before starting a new job.',
                'en_desc'        => '<p>CBC, blood group, hepatitis B & C, HIV, syphilis, blood sugar, liver enzymes, and urine analysis — the standard pre-employment health clearance panel.</p>',
                'ar_name'        => 'فحص ما قبل التوظيف',
                'ar_short'       => 'الفحص الصحي المهني المطلوب قبل الالتحاق بعمل جديد.',
                'ar_desc'        => '<p>صورة دم كاملة، فصيلة الدم، التهاب كبد B وC، HIV، الزهري، سكر الدم، إنزيمات الكبد، وتحليل البول — الفحص الصحي القياسي ما قبل التوظيف.</p>',
            ],

            // ─── Home Test Kits ─────────────────────────────────────────────
            [
                'slug'           => 'gut-inflammation-kit',
                'price'          => 0,
                'original_price' => null,
                'is_featured'    => false,
                'is_kit'         => true,
                'sample_type'    => 'stool',
                'sort_order'     => 41,
                'categories'     => ['kits', 'gut-health'],
                'en_name'        => 'Gut Inflammation Home Kit',
                'en_short'       => 'Home stool kit to detect intestinal inflammation (calprotectin).',
                'en_desc'        => '<p>Collect your stool sample at home and drop it off at any branch. Measures fecal calprotectin — a sensitive marker for intestinal inflammation conditions like IBD and Crohn\'s disease.</p>',
                'ar_name'        => 'مجموعة فحص التهاب الأمعاء المنزلية',
                'ar_short'       => 'مجموعة منزلية لاكتشاف التهاب الأمعاء (كالبروتكتين).',
                'ar_desc'        => '<p>اجمع عينة البراز في المنزل وأحضرها لأي فرع. يقيس الكالبروتكتين البرازي — مؤشر حساس لالتهابات الأمعاء كداء التهاب الأمعاء وكرون.</p>',
            ],
            [
                'slug'           => 'lactose-intolerance-kit',
                'price'          => 0,
                'original_price' => null,
                'is_featured'    => false,
                'is_kit'         => true,
                'sample_type'    => 'breath',
                'sort_order'     => 42,
                'categories'     => ['kits', 'allergy'],
                'en_name'        => 'Lactose Intolerance Home Kit',
                'en_short'       => 'At-home breath test kit for lactose intolerance.',
                'en_desc'        => '<p>Collect breath samples at timed intervals after a lactose challenge — the kit includes everything needed for an accurate hydrogen breath test for lactose intolerance.</p>',
                'ar_name'        => 'مجموعة فحص تعصب اللاكتوز المنزلية',
                'ar_short'       => 'مجموعة اختبار التنفس المنزلية لتعصب اللاكتوز.',
                'ar_desc'        => '<p>اجمع عينات الزفير على فترات زمنية محددة بعد جرعة اللاكتوز — تحتوي المجموعة على كل ما يلزم لإجراء اختبار الهيدروجين بالتنفس لتعصب اللاكتوز.</p>',
            ],
            [
                'slug'           => 'stomach-check-kit',
                'price'          => 0,
                'original_price' => null,
                'is_featured'    => false,
                'is_kit'         => true,
                'sample_type'    => 'breath',
                'sort_order'     => 43,
                'categories'     => ['kits', 'gut-health'],
                'en_name'        => 'Stomach H. Pylori Home Kit',
                'en_short'       => 'Home breath test kit for H. pylori detection.',
                'en_desc'        => '<p>A simple urea breath test kit you can use at home. Detects active H. pylori infection — the leading cause of peptic ulcers and stomach cancer.</p>',
                'ar_name'        => 'مجموعة فحص جرثومة المعدة المنزلية',
                'ar_short'       => 'مجموعة اختبار التنفس المنزلية للكشف عن H. pylori.',
                'ar_desc'        => '<p>مجموعة اختبار اليوريا بالتنفس البسيطة للاستخدام المنزلي. تكتشف عدوى H. pylori النشطة — السبب الرئيسي لقرحة المعدة وسرطانها.</p>',
            ],
            [
                'slug'           => 'stool-analysis-kit',
                'price'          => 0,
                'original_price' => null,
                'is_featured'    => false,
                'is_kit'         => true,
                'sample_type'    => 'stool',
                'sort_order'     => 44,
                'categories'     => ['kits'],
                'en_name'        => 'Stool Analysis Home Kit',
                'en_short'       => 'Home stool collection kit for comprehensive stool analysis.',
                'en_desc'        => '<p>Collect your stool sample in the comfort of your home using our sterile kit. Includes comprehensive stool analysis covering parasites, occult blood, white cells, and pH.</p>',
                'ar_name'        => 'مجموعة تحليل البراز المنزلية',
                'ar_short'       => 'مجموعة جمع البراز المنزلية لتحليل شامل.',
                'ar_desc'        => '<p>اجمع عينة البراز في المنزل باستخدام مجموعتنا المعقمة. تشمل تحليلاً شاملاً للبراز يغطي الطفيليات، الدم الخفي، كريات الدم البيضاء، ودرجة الحموضة.</p>',
            ],
            [
                'slug'           => 'stool-culture-kit',
                'price'          => 0,
                'original_price' => null,
                'is_featured'    => false,
                'is_kit'         => true,
                'sample_type'    => 'stool',
                'sort_order'     => 45,
                'categories'     => ['kits'],
                'en_name'        => 'Stool Culture Home Kit',
                'en_short'       => 'Home stool collection kit for bacterial culture and sensitivity.',
                'en_desc'        => '<p>Sterile stool collection kit for culture and antibiotic sensitivity testing. Identifies bacterial infections causing diarrhea or gastrointestinal symptoms.</p>',
                'ar_name'        => 'مجموعة زراعة البراز المنزلية',
                'ar_short'       => 'مجموعة جمع البراز المنزلية لزراعة البكتيريا والحساسية.',
                'ar_desc'        => '<p>مجموعة جمع البراز المعقمة لزراعة البكتيريا واختبار حساسية المضادات الحيوية. تحدد الالتهابات البكتيرية المسببة للإسهال أو الأعراض المعوية.</p>',
            ],
            [
                'slug'           => 'urine-culture-kit',
                'price'          => 0,
                'original_price' => null,
                'is_featured'    => false,
                'is_kit'         => true,
                'sample_type'    => 'urine',
                'sort_order'     => 46,
                'categories'     => ['kits'],
                'en_name'        => 'Urine Culture Home Kit',
                'en_short'       => 'Home urine collection kit for bacterial culture and sensitivity.',
                'en_desc'        => '<p>Sterile mid-stream urine collection kit for culture and antibiotic sensitivity testing. Identifies bacteria causing urinary tract infections (UTIs).</p>',
                'ar_name'        => 'مجموعة زراعة البول المنزلية',
                'ar_short'       => 'مجموعة جمع البول المنزلية لزراعة البكتيريا والحساسية.',
                'ar_desc'        => '<p>مجموعة جمع البول المعقمة وسط الإدرار لزراعة البكتيريا واختبار حساسية المضادات الحيوية. تحدد البكتيريا المسببة لالتهابات المسالك البولية.</p>',
            ],
            [
                'slug'           => 'urine-test-babies-kit',
                'price'          => 0,
                'original_price' => null,
                'is_featured'    => false,
                'is_kit'         => true,
                'sample_type'    => 'urine',
                'sort_order'     => 47,
                'categories'     => ['kits', 'kids'],
                'en_name'        => 'Urine Test for Babies Home Kit',
                'en_short'       => 'Specially designed home urine collection kit for infants and toddlers.',
                'en_desc'        => '<p>A sterile, paediatric-friendly urine collection bag designed for infants who cannot provide a standard mid-stream sample. Suitable for children under 3 years old.</p>',
                'ar_name'        => 'مجموعة تحليل بول الرضع المنزلية',
                'ar_short'       => 'مجموعة جمع بول منزلية مصممة خصيصاً للرضع والأطفال الصغار.',
                'ar_desc'        => '<p>كيس جمع بول معقم صديق للأطفال مصمم للرضع الذين لا يمكنهم تقديم عينة بول وسط اعتيادية. مناسب للأطفال دون سن 3 سنوات.</p>',
            ],
        ];

        foreach ($packages as $pkg) {
            // Skip if slug already exists
            if (DB::table('packages')->where('slug', $pkg['slug'])->exists()) {
                continue;
            }

            // Resolve category IDs
            $categoryIds = DB::table('test_categories')
                ->whereIn('slug', $pkg['categories'])
                ->pluck('id')
                ->toArray();

            $pkgId = DB::table('packages')->insertGetId([
                'slug'           => $pkg['slug'],
                'price'          => $pkg['price'] ?: null,
                'original_price' => $pkg['original_price'],
                'is_active'      => true,
                'is_featured'    => $pkg['is_featured'],
                'is_kit'         => $pkg['is_kit'],
                'sample_type'    => $pkg['sample_type'],
                'sort_order'     => $pkg['sort_order'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('package_translations')->insert([
                [
                    'package_id'        => $pkgId,
                    'locale'            => 'en',
                    'name'              => $pkg['en_name'],
                    'short_description' => $pkg['en_short'],
                    'description'       => $pkg['en_desc'],
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
                [
                    'package_id'        => $pkgId,
                    'locale'            => 'ar',
                    'name'              => $pkg['ar_name'],
                    'short_description' => $pkg['ar_short'],
                    'description'       => $pkg['ar_desc'],
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
            ]);

            foreach ($categoryIds as $catId) {
                DB::table('package_category')->insertOrIgnore([
                    'package_id'  => $pkgId,
                    'category_id' => $catId,
                ]);
            }
        }
    }
}
