<?php

namespace App\Services\Theme;

class ColorUtility
{
    public static function normalizeHex(string $hex): string
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (! preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            return '#000000';
        }

        return '#' . strtoupper($hex);
    }

    public static function hexToRgb(string $hex): array
    {
        $hex = ltrim(self::normalizeHex($hex), '#');

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    public static function rgbToHex(int $r, int $g, int $b): string
    {
        return sprintf('#%02X%02X%02X',
            max(0, min(255, $r)),
            max(0, min(255, $g)),
            max(0, min(255, $b)
        ));
    }

    public static function hexToHsl(string $hex): array
    {
        $rgb = self::hexToRgb($hex);
        $r = $rgb['r'] / 255;
        $g = $rgb['g'] / 255;
        $b = $rgb['b'] / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        $h = $s = 0;

        if ($max !== $min) {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

            switch ($max) {
                case $r: $h = (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6; break;
                case $g: $h = (($b - $r) / $d + 2) / 6; break;
                case $b: $h = (($r - $g) / $d + 4) / 6; break;
            }
        }

        return [
            'h' => round($h * 360),
            's' => round($s * 100),
            'l' => round($l * 100),
        ];
    }

    public static function hslToHex(int $h, int $s, int $l): string
    {
        $h = (($h % 360) + 360) % 360;
        $s = max(0, min(100, $s)) / 100;
        $l = max(0, min(100, $l)) / 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
        $m = $l - $c / 2;

        [$r, $g, $b] = match (true) {
            $h < 60  => [$c, $x, 0],
            $h < 120 => [$x, $c, 0],
            $h < 180 => [0, $c, $x],
            $h < 240 => [0, $x, $c],
            $h < 300 => [$x, 0, $c],
            default  => [$c, 0, $x],
        };

        return self::rgbToHex(
            (int) round(($r + $m) * 255),
            (int) round(($g + $m) * 255),
            (int) round(($b + $m) * 255)
        );
    }

    public static function lighten(string $hex, float $amount): string
    {
        $hsl = self::hexToHsl($hex);

        return self::hslToHex($hsl['h'], $hsl['s'], min(100, $hsl['l'] + $amount));
    }

    public static function darken(string $hex, float $amount): string
    {
        $hsl = self::hexToHsl($hex);

        return self::hslToHex($hsl['h'], $hsl['s'], max(0, $hsl['l'] - $amount));
    }

    public static function adjustSaturation(string $hex, float $amount): string
    {
        $hsl = self::hexToHsl($hex);

        return self::hslToHex($hsl['h'], max(0, min(100, $hsl['s'] + $amount)), $hsl['l']);
    }

    public static function mix(string $hex1, string $hex2, float $weight = 0.5): string
    {
        $rgb1 = self::hexToRgb($hex1);
        $rgb2 = self::hexToRgb($hex2);
        $w = max(0, min(1, $weight));

        return self::rgbToHex(
            (int) round($rgb1['r'] * (1 - $w) + $rgb2['r'] * $w),
            (int) round($rgb1['g'] * (1 - $w) + $rgb2['g'] * $w),
            (int) round($rgb1['b'] * (1 - $w) + $rgb2['b'] * $w)
        );
    }

    public static function relativeLuminance(string $hex): float
    {
        $rgb = self::hexToRgb($hex);
        $channels = [];

        foreach (['r', 'g', 'b'] as $c) {
            $v = $rgb[$c] / 255;
            $channels[] = $v <= 0.03928 ? $v / 12.92 : pow(($v + 0.055) / 1.055, 2.4);
        }

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    }

    public static function contrastRatio(string $hex1, string $hex2): float
    {
        $l1 = self::relativeLuminance($hex1);
        $l2 = self::relativeLuminance($hex2);
        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    public static function bestTextColor(string $background): string
    {
        $white = self::contrastRatio($background, '#FFFFFF');
        $black = self::contrastRatio($background, '#000000');

        return $white >= $black ? '#FFFFFF' : '#000000';
    }

    public static function ensureContrast(string $foreground, string $background, float $minRatio = 4.5): string
    {
        if (self::contrastRatio($foreground, $background) >= $minRatio) {
            return $foreground;
        }

        $fgLum = self::relativeLuminance($foreground);
        $target = $fgLum > 0.5 ? '#000000' : '#FFFFFF';

        if (self::contrastRatio($target, $background) >= $minRatio) {
            return $target;
        }

        $hsl = self::hexToHsl($foreground);
        $step = $fgLum > 0.5 ? -5 : 5;

        for ($i = 0; $i < 20; $i++) {
            $hsl['l'] = max(0, min(100, $hsl['l'] + $step));
            $candidate = self::hslToHex($hsl['h'], $hsl['s'], $hsl['l']);

            if (self::contrastRatio($candidate, $background) >= $minRatio) {
                return $candidate;
            }
        }

        return self::bestTextColor($background);
    }
}
