<?php

namespace App\Services\Theme;

class ColorGeneratorService
{
    public function generateFromPrimary(string $primary, string $previewMode = 'dark'): array
    {
        $primary = ColorUtility::normalizeHex($primary);
        $isLight = $previewMode === 'light';

        $hsl = ColorUtility::hexToHsl($primary);

        $secondary = ColorUtility::hslToHex($hsl['h'], max(20, $hsl['s'] - 10), max(10, $hsl['l'] - 12));
        $accent = ColorUtility::hslToHex(($hsl['h'] + 30) % 360, min(100, $hsl['s'] + 5), min(95, $hsl['l'] + 15));

        $btnHover = ColorUtility::darken($primary, 8);
        $btnActive = ColorUtility::darken($primary, 18);

        $background = $isLight
            ? ColorUtility::mix('#F8F9FA', ColorUtility::lighten($primary, 45), 0.92)
            : ColorUtility::mix('#0D0D0D', ColorUtility::darken($primary, 35), 0.88);

        $surface = $isLight
            ? '#FFFFFF'
            : ColorUtility::mix('#111111', ColorUtility::darken($primary, 40), 0.85);

        $textPrimary = ColorUtility::ensureContrast(
            $isLight ? '#212529' : '#FFFFFF',
            $background,
            7
        );

        $textSecondary = ColorUtility::ensureContrast(
            $isLight ? '#6C757D' : '#AAAAAA',
            $background,
            4.5
        );

        $heading = ColorUtility::ensureContrast(
            $isLight ? '#212529' : '#FFFFFF',
            $background,
            7
        );

        $border = $isLight
            ? ColorUtility::mix('#DEE2E6', $primary, 0.15)
            : ColorUtility::mix('#3A3A3A', $primary, 0.2);

        $btnText = ColorUtility::bestTextColor($primary);

        $inputBg = $isLight ? '#FFFFFF' : ColorUtility::mix('#222222', $surface, 0.5);
        $inputBorder = $isLight ? '#DEE2E6' : ColorUtility::mix('#3A3A3A', $border, 0.5);
        $inputPlaceholder = $isLight ? '#ADB5BD' : '#666666';
        $inputLabel = ColorUtility::ensureContrast(
            $isLight ? '#495057' : '#CCCCCC',
            $background,
            4.5
        );

        $navbarBg = $isLight ? '#FFFFFF' : ColorUtility::mix('#111111', $background, 0.3);
        $navbarText = ColorUtility::ensureContrast($textPrimary, $navbarBg, 4.5);

        $sidebarBg = $navbarBg;
        $sidebarText = $textSecondary;
        $sidebarHover = $isLight ? '#F1F3F5' : ColorUtility::mix('#2C2C2C', $primary, 0.08);
        $sidebarActive = $primary;

        $footerBg = $isLight ? '#F1F3F5' : ColorUtility::mix('#111111', $background, 0.4);
        $footerText = ColorUtility::ensureContrast($textSecondary, $footerBg, 4.5);
        $footerLinks = $primary;

        $generated = [
            'primary'            => $primary,
            'secondary'          => $secondary,
            'accent'             => $accent,
            'background'         => $background,
            'surface'            => $surface,
            'text_primary'       => $textPrimary,
            'text_secondary'     => $textSecondary,
            'heading'            => $heading,
            'border'             => $border,
            'success'            => '#28A745',
            'warning'            => '#FFC107',
            'danger'             => '#DC3545',
            'info'               => '#17A2B8',
            'btn_primary'        => $primary,
            'btn_secondary'      => $secondary,
            'btn_outline'        => $primary,
            'btn_hover'          => $btnHover,
            'btn_active'         => $btnActive,
            'btn_disabled'       => $isLight ? '#CED4DA' : '#555555',
            'btn_text'           => $btnText,
            'link_normal'        => $primary,
            'link_hover'         => ColorUtility::lighten($primary, 12),
            'link_active'        => $secondary,
            'input_bg'           => $inputBg,
            'input_border'       => $inputBorder,
            'input_focus_border' => $primary,
            'input_placeholder'  => $inputPlaceholder,
            'input_label'        => $inputLabel,
            'navbar_bg'          => $navbarBg,
            'navbar_text'        => $navbarText,
            'sidebar_bg'         => $sidebarBg,
            'sidebar_text'       => $sidebarText,
            'sidebar_active'     => $sidebarActive,
            'sidebar_hover'      => $sidebarHover,
            'footer_bg'          => $footerBg,
            'footer_text'        => $footerText,
            'footer_links'       => $footerLinks,
        ];

        return array_merge(config('theme.defaults', []), $generated);
    }

    /**
     * Generate a full color palette derived from a background color.
     *
     * Algorithm:
     *  1. Detect luminance & HSL of background to determine dark vs light.
     *  2. Pick an exquisite primary color (Luxury Gold for dark neutral, or
     *     harmonious complementary pop color for colored dark/light backgrounds).
     *  3. Calculate surface, navbar, footer, sidebar, border, and input shades
     *     that match the undertone of the background seamlessly.
     *  4. Enforce strict WCAG contrast for all text, buttons, and links.
     */
    public function generateFromBackground(string $background, ?string $forceMode = null): array
    {
        $background = ColorUtility::normalizeHex($background);
        $bgHsl      = ColorUtility::hexToHsl($background);

        // --- 1. Detect mode from luminance --------------------------------
        $luminance = ColorUtility::relativeLuminance($background);
        $isDark    = $luminance < 0.40;
        $mode      = $forceMode ?? ($isDark ? 'dark' : 'light');

        // --- 2. Derive a highly harmonious Primary color ------------------
        if ($isDark) {
            if ($bgHsl['s'] < 12) {
                // Neutral dark (Black, Charcoal, Dark Gray) -> Iconic Luxury Gold
                $primary = '#D4AF37';
            } else {
                // Colored dark (Navy, Emerald, Purple, Burgundy, etc.)
                // Shift hue to complementary pop zone (+140° to +160°)
                $primaryHue   = ($bgHsl['h'] + 150) % 360;
                $primarySat   = max(65, min(95, $bgHsl['s'] + 20));
                $primaryLight = 58; // Bright enough to radiate on dark bg
                $primary      = ColorUtility::hslToHex($primaryHue, $primarySat, $primaryLight);
            }
        } else {
            if ($bgHsl['s'] < 12) {
                // Neutral light (White, Cream, Light Gray) -> Rich Deep Gold / Bronze
                $primary = '#B8860B';
            } else {
                // Colored light background -> Rich deep contrasting primary
                $primaryHue   = ($bgHsl['h'] + 180) % 360;
                $primarySat   = max(60, min(90, $bgHsl['s'] + 15));
                $primaryLight = 36; // Dark enough to stand out on light bg
                $primary      = ColorUtility::hslToHex($primaryHue, $primarySat, $primaryLight);
            }
        }

        // --- 3. Generate full palette from primary -------------------------
        $palette = $this->generateFromPrimary($primary, $mode);

        // --- 4. Refine structural colors matching background undertones ---
        $hBg = $bgHsl['h'];
        $sBg = $bgHsl['s'];
        $lBg = $bgHsl['l'];

        if ($isDark) {
            // Layered depth for dark mode
            $surface     = ColorUtility::hslToHex($hBg, max(0, $sBg - 2), min(35, $lBg + 5));
            $navbarBg    = ColorUtility::hslToHex($hBg, $sBg, max(0, $lBg - 2));
            $footerBg    = ColorUtility::hslToHex($hBg, $sBg, max(0, $lBg - 3));
            $sidebarBg   = $surface;
            $inputBg     = ColorUtility::hslToHex($hBg, max(0, $sBg - 2), min(40, $lBg + 3));
            $border      = ColorUtility::hslToHex($hBg, max(0, $sBg - 2), min(50, $lBg + 12));
        } else {
            // Crisp clean light mode
            $surface     = '#FFFFFF';
            $navbarBg    = '#FFFFFF';
            $footerBg    = ColorUtility::hslToHex($hBg, max(0, $sBg - 5), max(85, $lBg - 4));
            $sidebarBg   = '#FFFFFF';
            $inputBg     = '#FFFFFF';
            $border      = ColorUtility::hslToHex($hBg, max(0, $sBg - 10), max(70, $lBg - 12));
        }

        $textPrimary   = ColorUtility::ensureContrast(
            $isDark ? '#FFFFFF' : '#111827', $background, 7.0
        );
        $textSecondary = ColorUtility::ensureContrast(
            $isDark ? '#A0AEC0' : '#4B5563', $background, 4.5
        );
        $heading       = $textPrimary;

        $inputBorder      = $border;
        $inputPlaceholder = $isDark ? '#64748B' : '#9CA3AF';
        $inputLabel       = ColorUtility::ensureContrast(
            $isDark ? '#CBD5E1' : '#374151', $background, 4.5
        );
        $navbarText  = ColorUtility::ensureContrast($textPrimary,   $navbarBg,  4.5);
        $footerText  = ColorUtility::ensureContrast($textSecondary, $footerBg,  4.5);
        $sidebarText = $textSecondary;
        $sidebarHover = $isDark
            ? ColorUtility::mix('#2C2C2C', $primary, 0.08)
            : '#F1F3F5';

        $palette['background']        = $background;
        $palette['surface']           = $surface;
        $palette['text_primary']      = $textPrimary;
        $palette['text_secondary']    = $textSecondary;
        $palette['heading']           = $heading;
        $palette['border']            = $border;
        $palette['navbar_bg']         = $navbarBg;
        $palette['navbar_text']       = $navbarText;
        $palette['sidebar_bg']        = $sidebarBg;
        $palette['sidebar_text']      = $sidebarText;
        $palette['sidebar_hover']     = $sidebarHover;
        $palette['footer_bg']         = $footerBg;
        $palette['footer_text']       = $footerText;
        $palette['input_bg']          = $inputBg;
        $palette['input_border']      = $inputBorder;
        $palette['input_placeholder'] = $inputPlaceholder;
        $palette['input_label']       = $inputLabel;

        return $palette;
    }

    /**
     * Convenience wrapper — generate full palette from background and
     * return it merged over the config defaults.
     */
    public function generateFullPaletteFromBackground(string $background): array
    {
        return $this->generateFromBackground($background);
    }

    public function mergeWithOverrides(array $generated, array $overrides, array $manualColors): array
    {
        $result = $generated;

        foreach ($manualColors as $key => $value) {
            if (! empty($overrides[$key]) && $value) {
                $result[$key] = ColorUtility::normalizeHex($value);
            }
        }

        return $result;
    }
}
