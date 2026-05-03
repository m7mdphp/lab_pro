<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();

            // Bilingual content
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('excerpt_ar')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();

            // Category / tag (free-text, bilingual)
            $table->string('category_ar')->nullable();
            $table->string('category_en')->nullable();

            // Author
            $table->string('author_ar')->nullable();
            $table->string('author_en')->nullable();

            // Media
            $table->string('featured_image')->nullable(); // stored path in 'public' disk
            $table->string('audio_file')->nullable();     // uploaded audio file path
            $table->string('audio_url')->nullable();      // external audio URL (YouTube, SoundCloud...)

            // Reading time (minutes, auto-calculated or set manually)
            $table->unsignedSmallInteger('read_time')->default(3);

            // SEO
            $table->string('seo_title_ar')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->text('seo_description_ar')->nullable();
            $table->text('seo_description_en')->nullable();

            // Status
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
