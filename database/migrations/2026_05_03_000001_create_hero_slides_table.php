<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->text('subtitle_ar')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->string('button_text_ar')->nullable()->default('احجز الآن');
            $table->string('button_text_en')->nullable()->default('Book Now');
            $table->string('button_url')->nullable()->default('/booking');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed 3 default slides
        DB::table('hero_slides')->insert([
            [
                'image'       => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=1920&q=80',
                'title_ar'    => 'نتائج دقيقة،<br>قرارات أسرع',
                'title_en'    => 'Accurate Results,<br>Faster Decisions',
                'subtitle_ar' => 'معامل الشيخة للتحاليل الطبية — دقة عالية وسرعة في الإنجاز وخدمة أخذ العينات المنزلية',
                'subtitle_en' => 'El-Sheikha Lab delivers precision diagnostics with speed and home collection service',
                'button_text_ar' => 'احجز الآن',
                'button_text_en' => 'Book Now',
                'button_url'  => '/booking',
                'sort_order'  => 0,
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'image'       => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=1920&q=80',
                'title_ar'    => 'معامل مؤتمتة بالكامل',
                'title_en'    => 'Fully Automated Labs',
                'subtitle_ar' => 'أجهزة حديثة تعمل 14 ساعة يومياً لضمان أعلى دقة في النتائج',
                'subtitle_en' => 'Modern equipment running 14 hours daily for maximum accuracy',
                'button_text_ar' => 'احجز الآن',
                'button_text_en' => 'Book Now',
                'button_url'  => '/booking',
                'sort_order'  => 1,
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'image'       => 'https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?w=1920&q=80',
                'title_ar'    => 'سحب العينات في منزلك',
                'title_en'    => 'Home Sample Collection',
                'subtitle_ar' => 'فريق طبي متخصص يأتيك أينما كنت — احجز الآن',
                'subtitle_en' => 'Specialized medical team comes to you — book now',
                'button_text_ar' => 'احجز الآن',
                'button_text_en' => 'Book Now',
                'button_url'  => '/booking',
                'sort_order'  => 2,
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
