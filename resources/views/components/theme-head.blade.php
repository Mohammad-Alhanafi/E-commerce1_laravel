{{--
    ============================================================
    components/theme-head.blade.php
    ────────────────────────────────────────────────────────────
    PURPOSE:
      Include this component at the TOP of every layout <head>.
      - Injects dynamic CSS Custom Properties (Theme Engine)
      - Dynamic Bootstrap 5 CSS (RTL for Arabic, LTR for English/French)
      - Anti-FOUC inline script for instant theme loading
      - Preloads Cairo & Poppins Google Fonts
    ============================================================
--}}

@php
    $locale = app()->getLocale();
    $isRtl  = ($locale === 'ar');
    $colors = $themeColors ?? \App\Models\Theme::getActiveColors();
@endphp

{{-- ── 1. Dynamic Bootstrap 5 (RTL / LTR) ───────────────────── --}}
@if($isRtl)
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
@else
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
@endif

{{-- ── 2. Load core theme.css early ────────────────────────── --}}
<link rel="stylesheet" href="{{ asset('css/theme.css') }}">

{{-- ── 3. Dynamic Theme Engine CSS Custom Properties ──────── --}}
<style id="ha-dynamic-theme-vars">
    :root,
    [data-theme="dark"] {
        --primary-color:   {{ $colors['primary_color']   ?? '#D4AF37' }};
        --secondary-color: {{ $colors['secondary_color'] ?? '#B8941E' }};
        --hover-color:     {{ $colors['hover_color']     ?? '#C89B2C' }};
        --success-color:   {{ $colors['success_color']   ?? '#28A745' }};
        --danger-color:    {{ $colors['danger_color']    ?? '#DC3545' }};
        --warning-color:   {{ $colors['warning_color']   ?? '#FFC107' }};
        --info-color:      {{ $colors['info_color']      ?? '#17A2B8' }};
        --bg-color:        {{ $colors['dark_bg']         ?? '#1A1A1A' }};
    }

    [data-theme="light"] {
        --primary-color:   {{ $colors['primary_color']   ?? '#D4AF37' }};
        --secondary-color: {{ $colors['secondary_color'] ?? '#B8941E' }};
        --hover-color:     {{ $colors['hover_color']     ?? '#C89B2C' }};
        --success-color:   {{ $colors['success_color']   ?? '#28A745' }};
        --danger-color:    {{ $colors['danger_color']    ?? '#DC3545' }};
        --warning-color:   {{ $colors['warning_color']   ?? '#FFC107' }};
        --info-color:      {{ $colors['info_color']      ?? '#17A2B8' }};
        --bg-color:        {{ $colors['light_bg']        ?? '#F8F9FA' }};
    }

    /* Bootstrap 5 Utility Overrides */
    .bg-primary { background-color: var(--primary-color) !important; }
    .btn-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; color: #000 !important; }
    .btn-primary:hover { background-color: var(--hover-color) !important; border-color: var(--hover-color) !important; }
    .text-primary { color: var(--primary-color) !important; }
    .border-primary { border-color: var(--primary-color) !important; }
    .btn-outline-primary { color: var(--primary-color) !important; border-color: var(--primary-color) !important; }
    .btn-outline-primary:hover { background-color: var(--primary-color) !important; color: #000 !important; }
</style>

{{-- ── 4. Anti-FOUC script ────────────────────────────────── --}}
<script>
(function() {
    var saved = localStorage.getItem('ha_theme');
    var theme = saved;

    if (!theme) {
        theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches)
            ? 'light'
            : 'dark';
    }

    document.documentElement.setAttribute('data-theme', theme);
})();
</script>

{{-- ── 5. Google Fonts (Cairo for Arabic, Poppins for English/French) ── --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
