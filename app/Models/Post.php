<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'slug',
        'title_ar', 'title_en',
        'excerpt_ar', 'excerpt_en',
        'content_ar', 'content_en',
        'category_ar', 'category_en',
        'author_ar', 'author_en',
        'featured_image',
        'audio_file', 'audio_url',
        'read_time',
        'seo_title_ar', 'seo_title_en',
        'seo_description_ar', 'seo_description_en',
        'is_published',
        'published_at',
        'sort_order',
    ];

    protected $casts = [
        'is_published'  => 'boolean',
        'published_at'  => 'datetime',
        'read_time'     => 'integer',
        'sort_order'    => 'integer',
    ];

    // ── Scopes ──────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->orderByDesc('published_at');
    }

    // ── Locale helpers ───────────────────────────────────────
    public function getTitle(): string
    {
        $l = app()->getLocale();
        return ($l === 'ar' ? $this->title_ar : ($this->title_en ?: $this->title_ar)) ?? '';
    }

    public function getExcerpt(): ?string
    {
        $l = app()->getLocale();
        return $l === 'ar' ? $this->excerpt_ar : ($this->excerpt_en ?: $this->excerpt_ar);
    }

    public function getContent(): ?string
    {
        $l = app()->getLocale();
        return $l === 'ar' ? $this->content_ar : ($this->content_en ?: $this->content_ar);
    }

    public function getCategory(): ?string
    {
        $l = app()->getLocale();
        return $l === 'ar' ? $this->category_ar : ($this->category_en ?: $this->category_ar);
    }

    public function getAuthor(): ?string
    {
        $l = app()->getLocale();
        return $l === 'ar' ? $this->author_ar : ($this->author_en ?: $this->author_ar);
    }

    public function getSeoTitle(): string
    {
        $l = app()->getLocale();
        $seo = $l === 'ar' ? $this->seo_title_ar : ($this->seo_title_en ?: $this->seo_title_ar);
        return $seo ?: $this->getTitle();
    }

    public function getSeoDescription(): string
    {
        $l = app()->getLocale();
        $seo = $l === 'ar' ? $this->seo_description_ar : ($this->seo_description_en ?: $this->seo_description_ar);
        return $seo ?: ($this->getExcerpt() ?? Str::limit(strip_tags($this->getContent() ?? ''), 160));
    }

    // ── Image URL ─────────────────────────────────────────────
    public function getFeaturedImageUrl(): ?string
    {
        if (!$this->featured_image) return null;
        if (str_starts_with($this->featured_image, 'http')) return $this->featured_image;
        return asset('storage/' . $this->featured_image);
    }

    // ── Audio URL (upload or external) ───────────────────────
    public function getAudioUrl(): ?string
    {
        if ($this->audio_url) return $this->audio_url;
        if ($this->audio_file) {
            if (str_starts_with($this->audio_file, 'http')) return $this->audio_file;
            return asset('storage/' . $this->audio_file);
        }
        return null;
    }

    public function hasAudio(): bool
    {
        return !empty($this->audio_url) || !empty($this->audio_file);
    }
}
