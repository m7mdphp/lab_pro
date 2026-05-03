<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();

            // Bilingual name + title
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('title_ar')->nullable();   // Job title / role
            $table->string('title_en')->nullable();
            $table->string('specialty_ar')->nullable(); // e.g., "طب المناعة"
            $table->string('specialty_en')->nullable();
            $table->text('bio_ar')->nullable();
            $table->text('bio_en')->nullable();

            // Media + social
            $table->string('image')->nullable();       // stored path in 'public' disk
            $table->string('linkedin_url')->nullable();

            // Display
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
