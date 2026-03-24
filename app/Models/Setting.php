<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /** @var array<int, string> */
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    /**
     * Get a setting value by group and key.
     */
    public static function get(string $group, string $key, mixed $default = null): mixed
    {
        $cacheKey = "settings.{$group}.{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($group, $key, $default) {
            $setting = static::where('group', $group)->where('key', $key)->first();

            if (! $setting) {
                return $default;
            }

            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $group, string $key, mixed $value, string $type = 'string'): void
    {
        $storedValue = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json', 'array' => json_encode($value),
            default => (string) $value,
        };

        static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $storedValue, 'type' => $type]
        );

        Cache::forget("settings.{$group}.{$key}");
    }

    /**
     * Cast the value to its proper type.
     */
    protected static function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'integer', 'int' => (int) $value,
            'boolean', 'bool' => $value === '1' || $value === 'true',
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Get all settings for a group.
     */
    public static function getGroup(string $group): array
    {
        $settings = static::where('group', $group)->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = static::castValue($setting->value, $setting->type);
        }

        return $result;
    }

    /**
     * Clear settings cache for a group.
     */
    public static function clearGroupCache(string $group): void
    {
        $settings = static::where('group', $group)->get();

        foreach ($settings as $setting) {
            Cache::forget("settings.{$group}.{$setting->key}");
        }
    }
}
