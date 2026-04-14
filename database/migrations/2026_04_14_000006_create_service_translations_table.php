<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name', 255);
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('features')->nullable();   // JSON array of bullet-point features
            $table->timestamps();

            $table->unique(['service_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_translations');
    }
};
