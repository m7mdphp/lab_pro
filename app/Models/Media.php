<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'disk', 'path', 'filename', 'mime_type', 'file_size_bytes',
        'width', 'height', 'content_hash', 'source_url',
        'referenced_by_entity', 'referenced_by_slug', 'referenced_by_field',
        'alt_en', 'alt_ar', 'is_active', 'is_optimized_jpeg', 'is_optimized_webp',
    ];

    public function getUrlAttribute(): ?string
    {
        if (!$this->path) return null;
        return $this->disk === 'public'
            ? asset('storage/' . $this->path)
            : $this->source_url;
    }

    public function getAltAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->alt_ar : $this->alt_en;
    }
}
