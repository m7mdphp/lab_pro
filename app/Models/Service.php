<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['slug', 'icon', 'color', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function translations(): HasMany
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    public function trans(): ?ServiceTranslation
    {
        $locale = app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }

    public function getNameAttribute(): string
    {
        return $this->trans()?->name ?? ucwords(str_replace('-', ' ', $this->slug));
    }

    public function getShortDescriptionAttribute(): ?string
    {
        return $this->trans()?->short_description;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->trans()?->description;
    }

    public function getFeaturesAttribute(): array
    {
        $raw = $this->trans()?->features;
        if (is_array($raw)) return $raw;
        if (is_string($raw)) return json_decode($raw, true) ?? [];
        return [];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
