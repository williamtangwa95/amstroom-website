<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

if (!function_exists('setting')) {
    /**
     * Get a setting value by its key with caching.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Cache::remember("settings:{$key}", 86400, function () use ($key, $default) {
            try {
                if (Schema::hasTable('settings')) {
                    $setting = Setting::where('key', $key)->first();
                    return $setting ? $setting->value : $default;
                }
            } catch (\Exception $e) {
                // Fail silently during initial migration/setup phases
            }
            return $default;
        });
    }
}
