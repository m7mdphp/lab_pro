<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected $fillable = [
        'slug', 'is_active', 'sort_order', 'website_url', 'logo_url', 'phone',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function translations(): HasMany
    {
        return $this->hasMany(PartnerTranslation::class);
    }

    public function trans(): ?PartnerTranslation
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

    public function getSpecialtyAttribute(): ?string
    {
        return $this->trans()?->specialty;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
