<?php

namespace App\Services\Theme;

class CssVariableBuilder
{
    public function build(array $colors, ?string $mode = null): string
    {
        $cssMap = config('theme.css_map', []);
        $legacyMap = config('theme.legacy_css_map', []);
        $lines = [];

        foreach ($cssMap as $key => $varName) {
            if (isset($colors[$key])) {
                $lines[] = sprintf('    %s: %s;', $varName, $colors[$key]);
            }
        }

        foreach ($legacyMap as $key => $varName) {
            if (isset($colors[$key])) {
                $lines[] = sprintf('    %s: %s;', $varName, $colors[$key]);
            }
        }

        $selector = $mode === 'light'
            ? '[data-theme="light"]'
            : ($mode === 'dark' ? ':root, [data-theme="dark"]' : ':root, [data-theme="dark"], [data-theme="light"]');

        return $selector . " {\n" . implode("\n", $lines) . "\n}";
    }

    public function buildStylesheet(array $darkColors, array $lightColors): string
    {
        $darkBlock = $this->buildForMode($darkColors, 'dark');
        $lightBlock = $this->buildForMode($lightColors, 'light');

        return $darkBlock . "\n\n" . $lightBlock;
    }

    protected function buildForMode(array $colors, string $mode): string
    {
        $cssMap = config('theme.css_map', []);
        $legacyMap = config('theme.legacy_css_map', []);
        $lines = [];

        foreach ($cssMap as $key => $varName) {
            if (isset($colors[$key])) {
                $lines[] = sprintf('    %s: %s;', $varName, $colors[$key]);
            }
        }

        foreach ($legacyMap as $key => $varName) {
            if (isset($colors[$key])) {
                $lines[] = sprintf('    %s: %s;', $varName, $colors[$key]);
            }
        }

        $selector = $mode === 'light'
            ? '[data-theme="light"]'
            : ':root, [data-theme="dark"]';

        return $selector . " {\n" . implode("\n", $lines) . "\n}";
    }
}
