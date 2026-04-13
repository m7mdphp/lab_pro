<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('disk', 50)->default('local');
            $table->string('path', 500)->nullable();
            $table->string('filename', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size_bytes')->nullable();
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('content_hash', 64)->nullable()->index();
            $table->text('source_url')->nullable();
            $table->string('referenced_by_entity', 50)->nullable();
            $table->string('referenced_by_slug', 255)->nullable();
            $table->string('referenced_by_field', 100)->nullable();
            $table->string('alt_en', 500)->nullable();
            $table->string('alt_ar', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_optimized_jpeg')->default(false);
            $table->boolean('is_optimized_webp')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
