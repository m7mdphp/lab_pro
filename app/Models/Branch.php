<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'slug', 'is_active', 'sort_order', 'phone', 'whatsapp',
        'email', 'latitude', 'longitude', 'google_maps_url',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function translations(): HasMany
    {
        return $this->hasMany(BranchTranslation::class);
    }

    public function trans(): ?BranchTranslation
    {
        $locale = app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }

    public function getNameAttribute(): string
    {
        return $this->trans()?->name ?? ucwords(str_replace('-', ' ', $this->slug));
    }

    public function getAddressAttribute(): ?string
    {
        return $this->trans()?->address;
    }

    public function getCityAttribute(): ?string
    {
        return $this->trans()?->city;
    }

    public function getWorkingHoursAttribute(): ?string
    {
        return $this->trans()?->working_hours;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
