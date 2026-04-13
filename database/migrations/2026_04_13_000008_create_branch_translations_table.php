<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('area', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('working_hours', 500)->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_translations');
    }
};
