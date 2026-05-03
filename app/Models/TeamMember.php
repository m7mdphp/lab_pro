<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name_ar', 'name_en',
        'title_ar', 'title_en',
        'specialty_ar', 'specialty_en',
        'bio_ar', 'bio_en',
        'image',
        'linkedin_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Scopes ───────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // ── Locale helpers ───────────────────────────────────────
    public function getName(): string
    {
        $l = app()->getLocale();
        return ($l === 'ar' ? $this->name_ar : ($this->name_en ?: $this->name_ar)) ?? '';
    }

    public function getJobTitle(): ?string
    {
        $l = app()->getLocale();
        return $l === 'ar' ? $this->title_ar : ($this->title_en ?: $this->title_ar);
    }

    public function getSpecialty(): ?string
    {
        $l = app()->getLocale();
        return $l === 'ar' ? $this->specialty_ar : ($this->specialty_en ?: $this->specialty_ar);
    }

    public function getBio(): ?string
    {
        $l = app()->getLocale();
        return $l === 'ar' ? $this->bio_ar : ($this->bio_en ?: $this->bio_ar);
    }

    // ── Image URL ─────────────────────────────────────────────
    public function getImageUrl(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('storage/' . $this->image);
    }
}
