<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_category', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('test_categories')->cascadeOnDelete();
            $table->primary(['package_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_category');
    }
};
