<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 50);
            $table->string('entity_slug', 255);
            $table->string('meta_title_en', 255)->nullable();
            $table->string('meta_title_ar', 255)->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->text('canonical_url')->nullable();
            $table->boolean('noindex_en')->default(false);
            $table->boolean('noindex_ar')->default(true);
            $table->timestamps();

            $table->unique(['entity_type', 'entity_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
