<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name', 255)->nullable();
            $table->timestamps();

            $table->unique(['partner_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_translations');
    }
};
