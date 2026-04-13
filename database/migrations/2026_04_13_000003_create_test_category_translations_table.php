<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_category_id')->constrained('test_categories')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->unique(['test_category_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_category_translations');
    }
};
