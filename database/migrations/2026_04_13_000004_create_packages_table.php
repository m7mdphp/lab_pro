<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->unsignedInteger('price')->nullable()->comment('In piastres (1/100 EGP)');
            $table->unsignedInteger('original_price')->nullable()->comment('In piastres');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_kit')->default(false);
            $table->string('sample_type', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->foreignId('thumbnail_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('source_url')->nullable();
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->char('quality_grade', 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
