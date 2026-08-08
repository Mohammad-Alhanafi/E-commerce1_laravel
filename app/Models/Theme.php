<?php

namespace App\Models;

use App\Services\Theme\ColorGeneratorService;
use App\Services\Theme\ColorUtility;
use App\Services\Theme\CssVariableBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'mode',
        'colors',
        'overrides',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'colors'    => 'array',
        'overrides' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * @deprecated Use config('theme.defaults') instead
     */
    public static array $defaults = [];

    public function getMergedColors(): array
    {
        return array_merge(config('theme.defaults', []), $this->colors ?? []);
    }

    public function getPreviewSwatch(): array
    {
        $colors = $this->getMergedColors();

        return [
            $colors['primary'] ?? '#D4AF37',
            $colors['secondary'] ?? '#B8941E',
            $colors['background'] ?? '#1A1A1A',
            $colors['accent'] ?? '#E8C547',
            $colors['surface'] ?? '#111111',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            static::clearThemeCache();
        });

        static::deleted(function () {
            static::clearThemeCache();
        });
    }

    public static function clearThemeCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('ha_active_theme_colors');
        \Illuminate\Support\Facades\Cache::forget('ha_active_theme_model');
        \Illuminate\Support\Facades\Cache::forget('ha_active_theme_css');
    }

    public static function getActive(): ?self
    {
        return \Illuminate\Support\Facades\Cache::remember('ha_active_theme_model', 86400, function () {
            try {
                return self::where('is_active', true)->first();
            } catch (\Throwable) {
                return null;
            }
        });
    }

    /**
     * Get resolved active theme colors for frontend injection.
     */
    public static function getActiveColors(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('ha_active_theme_colors', 86400, function () {
            try {
                $theme = self::getActive();

                if ($theme) {
                    $defaults = config('theme.defaults', []);
                    $stored = $theme->getMergedColors();

                    return array_merge($defaults, $stored);
                }
            } catch (\Throwable) {
                // fallback
            }

            return config('theme.defaults', []);
        });
    }

    public static function getActiveCssVariables(): string
    {
        return \Illuminate\Support\Facades\Cache::remember('ha_active_theme_css', 86400, function () {
            try {
                $theme = self::getActive();

                if ($theme) {
                    $service = app(\App\Services\Theme\ThemeService::class);
                    return $service->previewCss($theme);
                }
            } catch (\Throwable) {
                // fallback
            }

            $builder = app(CssVariableBuilder::class);
            $defaults = config('theme.defaults', []);
            $light = array_merge($defaults, config('theme.light_overrides', []));

            return $builder->buildStylesheet($defaults, $light);
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
