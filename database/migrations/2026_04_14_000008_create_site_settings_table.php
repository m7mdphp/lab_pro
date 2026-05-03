<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            // Contact
            ['key' => 'hotline',        'value' => '19XXX',                                         'group' => 'contact'],
            ['key' => 'whatsapp',       'value' => '01000000000',                                   'group' => 'contact'],
            ['key' => 'email',          'value' => 'info@elsheikhalab.com',                         'group' => 'contact'],
            ['key' => 'address_ar',     'value' => 'معامل الشيخة للتحاليل الطبية — مصر',           'group' => 'contact'],
            ['key' => 'address_en',     'value' => 'El-Sheikha Lab, Egypt',                         'group' => 'contact'],
            ['key' => 'working_hours_ar','value' => 'السبت – الخميس: 7 ص – 9 م',                  'group' => 'contact'],
            ['key' => 'working_hours_en','value' => 'Sat – Thu: 7 AM – 9 PM',                       'group' => 'contact'],

            // Social media
            ['key' => 'facebook_url',   'value' => '#',                                             'group' => 'social'],
            ['key' => 'instagram_url',  'value' => '#',                                             'group' => 'social'],
            ['key' => 'whatsapp_url',   'value' => '#',                                             'group' => 'social'],
            ['key' => 'youtube_url',    'value' => '#',                                             'group' => 'social'],

            // SEO / branding
            ['key' => 'site_name_ar',   'value' => 'معامل الشيخة للتحاليل الطبية',                 'group' => 'seo'],
            ['key' => 'site_name_en',   'value' => 'El-Sheikha Lab',                                'group' => 'seo'],
            ['key' => 'tagline_ar',     'value' => 'نتائج دقيقة، قرارات أسرع',                    'group' => 'seo'],
            ['key' => 'tagline_en',     'value' => 'Accurate Results, Faster Decisions',            'group' => 'seo'],
            ['key' => 'branches_count', 'value' => '4',                                             'group' => 'general'],
            ['key' => 'founded_year',   'value' => '2010',                                            'group' => 'seo'],

            // Stats / numbers
            ['key' => 'stat_tests_count',             'value' => '300+',      'group' => 'stats'],
            ['key' => 'stat_analyses_done',           'value' => '1M+',       'group' => 'stats'],
            ['key' => 'stat_avg_time',                'value' => '24 h',      'group' => 'stats'],
            ['key' => 'stat_total_analyses_milestone','value' => '+1,000,000','group' => 'stats'],
        ];

        foreach ($defaults as $setting) {
            DB::table('site_settings')->insertOrIgnore($setting);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
