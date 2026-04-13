<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestCategoryTranslation extends Model
{
    protected $table = 'test_category_translations';

    protected $fillable = ['test_category_id', 'locale', 'name', 'description', 'meta_title', 'meta_description'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class, 'test_category_id');
    }
}
