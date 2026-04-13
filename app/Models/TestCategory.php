<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TestCategory extends Model
{
    protected $table = 'test_categories';

    protected $fillable = ['slug', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function translations(): HasMany
    {
        return $this->hasMany(TestCategoryTranslation::class, 'test_category_id');
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_category', 'category_id', 'package_id');
    }

    /** Returns the translation for the current app locale, falling back to English. */
    public function trans(): ?TestCategoryTranslation
    {
        $locale = app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }

    public function getNameAttribute(): string
    {
        return $this->trans()?->name ?? ucwords(str_replace('-', ' ', $this->slug));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
