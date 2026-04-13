<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faq extends Model
{
    protected $fillable = ['sort_order', 'is_active', 'category'];

    protected $casts = ['is_active' => 'boolean'];

    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public function trans(): ?FaqTranslation
    {
        $locale = app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }

    public function getQuestionAttribute(): ?string
    {
        return $this->trans()?->question;
    }

    public function getAnswerAttribute(): ?string
    {
        return $this->trans()?->answer;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
