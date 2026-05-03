<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $table = 'site_settings';
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("site_setting:{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value (and clear cache).
     */
    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        Cache::forget("site_setting:{$key}");
    }

    /**
     * Get all settings as a key-value array, optionally filtered by group.
     */
    public static function allKeyed(?string $group = null): array
    {
        $query = static::query();
        if ($group) {
            $query->where('group', $group);
        }
        return $query->pluck('value', 'key')->all();
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        foreach (static::pluck('key') as $key) {
            Cache::forget("site_setting:{$key}");
        }
    }
}
