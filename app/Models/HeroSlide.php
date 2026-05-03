<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'image',
        'title_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_en',
        'button_text_ar',
        'button_text_en',
        'button_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Returns a public URL for the image.
     * Handles both storage paths (hero-slides/file.jpg) and full https:// URLs.
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=1920&q=80';
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }

    /**
     * Return all active slides ordered by sort_order.
     */
    public static function active(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }
}
