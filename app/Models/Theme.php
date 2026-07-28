<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'hover_color',
        'success_color',
        'danger_color',
        'warning_color',
        'info_color',
        'dark_bg',
        'light_bg',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Default color values fallback.
     */
    public static array $defaults = [
        'primary_color'   => '#D4AF37',
        'secondary_color' => '#B8941E',
        'hover_color'     => '#C89B2C',
        'success_color'   => '#28A745',
        'danger_color'    => '#DC3545',
        'warning_color'   => '#FFC107',
        'info_color'      => '#17A2B8',
        'dark_bg'         => '#1A1A1A',
        'light_bg'        => '#F8F9FA',
    ];

    /**
     * Get the active theme palette array with safe fallback defaults.
     */
    public static function getActiveColors(): array
    {
        try {
            if (Schema::hasTable('themes')) {
                $theme = self::where('is_active', true)->first();
                if ($theme) {
                    return [
                        'primary_color'   => $theme->primary_color ?? self::$defaults['primary_color'],
                        'secondary_color' => $theme->secondary_color ?? self::$defaults['secondary_color'],
                        'hover_color'     => $theme->hover_color ?? self::$defaults['hover_color'],
                        'success_color'   => $theme->success_color ?? self::$defaults['success_color'],
                        'danger_color'    => $theme->danger_color ?? self::$defaults['danger_color'],
                        'warning_color'   => $theme->warning_color ?? self::$defaults['warning_color'],
                        'info_color'      => $theme->info_color ?? self::$defaults['info_color'],
                        'dark_bg'         => $theme->dark_bg ?? self::$defaults['dark_bg'],
                        'light_bg'        => $theme->light_bg ?? self::$defaults['light_bg'],
                    ];
                }
            }
        } catch (\Throwable $e) {
            // DB connection or migration not run yet
        }

        return self::$defaults;
    }
}
