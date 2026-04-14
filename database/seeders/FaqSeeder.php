<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'sort' => 1, 'category' => 'general',
                'en_q' => 'Do I need to fast before my tests?',
                'en_a' => 'Most blood tests such as blood glucose, cholesterol, and triglycerides require fasting for 10–12 hours beforehand. You may drink plain water. Please check your specific test requirements when booking.',
                'ar_q' => 'هل أحتاج إلى الصيام قبل إجراء التحاليل؟',
                'ar_a' => 'معظم تحاليل الدم كسكر الدم والكوليسترول والثلاثيات الدهنية تستلزم الصيام من 10 إلى 12 ساعة مع السماح بشرب الماء. يرجى التحقق من متطلبات تحليلك المحدد عند الحجز.',
            ],
            [
                'sort' => 2, 'category' => 'general',
                'en_q' => 'How long does it take to get my results?',
                'en_a' => 'Most routine blood tests are ready within 3–5 working hours. Specialized tests such as cultures, hormonal panels, or genetic tests may take 1–3 working days. You will be notified as soon as your results are ready.',
                'ar_q' => 'كم من الوقت تستغرق استلام النتائج؟',
                'ar_a' => 'معظم تحاليل الدم الروتينية تكون جاهزة في غضون 3–5 ساعات عمل. التحاليل المتخصصة كالزراعات والهرمونات والتحاليل الجينية قد تستغرق 1–3 أيام عمل. سيتم إبلاغك فور جاهزية النتائج.',
            ],
            [
                'sort' => 3, 'category' => 'general',
                'en_q' => 'Do you offer home collection services?',
                'en_a' => 'Yes! We offer home collection for most tests. A trained phlebotomist visits your home or office at your preferred time. A small additional service fee applies. You can book a home visit through our website or by calling our hotline.',
                'ar_q' => 'هل تقدمون خدمة سحب العينات في المنزل؟',
                'ar_a' => 'نعم! نقدم خدمة سحب العينات في المنزل لمعظم التحاليل. يزور فني متدرب منزلك أو مكتبك في الوقت الذي يناسبك مقابل رسوم خدمة بسيطة. يمكنك الحجز عبر موقعنا أو بالاتصال بخطنا الساخن.',
            ],
            [
                'sort' => 4, 'category' => 'general',
                'en_q' => 'How can I receive my results?',
                'en_a' => 'Results are sent directly to your email as a secure PDF. You can also access them through your online patient account on our website, or collect a printed copy from any of our branches.',
                'ar_q' => 'كيف يمكنني استلام نتائجي؟',
                'ar_a' => 'ترسل النتائج إلى بريدك الإلكتروني بصيغة PDF آمنة. كما يمكنك الاطلاع عليها عبر حسابك في موقعنا، أو استلام نسخة مطبوعة من أي فرع.',
            ],
            [
                'sort' => 5, 'category' => 'general',
                'en_q' => 'What are your working hours?',
                'en_a' => 'Our branches are open Saturday through Thursday from 8:00 AM to 11:00 PM, and Friday from 10:00 AM to 6:00 PM. Home collection is available 7 days a week.',
                'ar_q' => 'ما هي أوقات العمل لديكم؟',
                'ar_a' => 'فروعنا مفتوحة من السبت إلى الخميس من الساعة 8 صباحاً حتى 11 مساءً، وأيام الجمعة من 10 صباحاً حتى 6 مساءً. خدمة المنزل متاحة 7 أيام في الأسبوع.',
            ],
            [
                'sort' => 6, 'category' => 'general',
                'en_q' => 'What payment methods do you accept?',
                'en_a' => 'We accept cash, credit and debit cards (Visa, Mastercard), Fawry, and mobile wallets (Vodafone Cash, Orange Cash, Etisalat Cash). Corporate and insurance agreements are also available.',
                'ar_q' => 'ما هي طرق الدفع المتاحة؟',
                'ar_a' => 'نقبل النقد، بطاقات الائتمان والخصم (فيزا وماستركارد)، فوري، والمحافظ الرقمية (فودافون كاش، أورانج كاش، اتصالات كاش). كما يتوفر تعاقد مع الشركات وشركات التأمين.',
            ],
            [
                'sort' => 7, 'category' => 'booking',
                'en_q' => 'How do I book a test?',
                'en_a' => 'You can book online through our website by clicking the "Book a Test" button, call our hotline, visit any branch directly, or use our home collection service through the website.',
                'ar_q' => 'كيف أحجز تحليلاً؟',
                'ar_a' => 'يمكنك الحجز عبر الإنترنت من خلال موقعنا بالضغط على زر "احجز الآن"، أو الاتصال بخطنا الساخن، أو زيارة أي فرع مباشرةً، أو استخدام خدمة سحب العينات في المنزل عبر الموقع.',
            ],
            [
                'sort' => 8, 'category' => 'booking',
                'en_q' => 'Can I cancel or reschedule my appointment?',
                'en_a' => 'Yes. You can cancel or reschedule up to 2 hours before your appointment by contacting us via phone, WhatsApp, or email. For home collection visits, please notify us at least 3 hours in advance.',
                'ar_q' => 'هل يمكنني إلغاء أو تغيير موعدي؟',
                'ar_a' => 'نعم. يمكنك الإلغاء أو إعادة الجدولة حتى ساعتين قبل موعدك بالتواصل معنا عبر الهاتف أو واتساب أو البريد الإلكتروني. بالنسبة لزيارات المنزل، يرجى إبلاغنا قبل 3 ساعات على الأقل.',
            ],
        ];

        foreach ($faqs as $faq) {
            $id = DB::table('faqs')->insertGetId([
                'sort_order' => $faq['sort'],
                'is_active'  => true,
                'category'   => $faq['category'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('faq_translations')->insert([
                [
                    'faq_id'     => $id,
                    'locale'     => 'en',
                    'question'   => $faq['en_q'],
                    'answer'     => $faq['en_a'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'faq_id'     => $id,
                    'locale'     => 'ar',
                    'question'   => $faq['ar_q'],
                    'answer'     => $faq['ar_a'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
