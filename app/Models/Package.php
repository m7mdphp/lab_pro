<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    protected $fillable = [
        'slug', 'price', 'original_price', 'is_active', 'is_featured',
        'is_kit', 'sample_type', 'sort_order', 'thumbnail_media_id',
        'source_url', 'quality_score', 'quality_grade',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'is_kit'      => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PackageTranslation::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TestCategory::class, 'package_category', 'package_id', 'category_id');
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'thumbnail_media_id');
    }

    /** Returns the translation for the current app locale, falling back to English. */
    public function trans(): ?PackageTranslation
    {
        $locale = app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }

    public function getNameAttribute(): string
    {
        return $this->trans()?->name ?? ucwords(str_replace('-', ' ', $this->slug));
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->trans()?->description;
    }

    public function getShortDescriptionAttribute(): ?string
    {
        return $this->trans()?->short_description;
    }

    /** Price in EGP (stored as piastres). */
    public function getPriceEgpAttribute(): ?float
    {
        return $this->price !== null ? $this->price / 100 : null;
    }

    public function getOriginalPriceEgpAttribute(): ?float
    {
        return $this->original_price !== null ? $this->original_price / 100 : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
