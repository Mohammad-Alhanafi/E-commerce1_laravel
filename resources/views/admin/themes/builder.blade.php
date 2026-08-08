{{-- Admin Theme Builder — v4.0 (Background-Driven Palette) --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Builder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: var(--bg-color, #0b0b0d);
            --surface: var(--card-bg, #17171a);
            --border: var(--border-color, rgba(201,162,39,.18));
            --gold: var(--primary-color, #c9a227);
            --gold-dim: color-mix(in srgb, var(--primary-color) 12%, transparent);
            --ink: var(--text-color, #f3efe4);
            --ink-muted: var(--text-muted, #a8a297);
            --ink-faint: var(--text-muted, #6b6560);
            --radius: 10px;
            --radius-lg: 18px;
            --transition: .18s ease;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg);
            color: var(--ink);
            margin: 0; padding: 0;
        }

        /* ── Layout ── */
        .builder-container { display: flex; height: 100vh; overflow: hidden; }

        /* ── Sidebar ── */
        .config-sidebar {
            width: 360px;
            background: var(--surface);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 10;
            flex-shrink: 0;
        }

        .config-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(201,162,39,.04);
        }
        .config-header h2 { margin: 0; font-size: 17px; font-weight: 700; }
        .btn-close-x {
            color: var(--ink-muted);
            text-decoration: none;
            font-size: 20px;
            transition: color var(--transition);
        }
        .btn-close-x:hover { color: var(--ink); }

        .config-body { flex: 1; overflow-y: auto; padding: 12px 14px; }
        .config-body::-webkit-scrollbar { width: 5px; }
        .config-body::-webkit-scrollbar-track { background: transparent; }
        .config-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* ── Accordion ── */
        .accordion-item {
            margin-bottom: 8px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .accordion-header {
            background: rgba(255,255,255,.02);
            padding: 12px 15px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
            transition: background var(--transition);
        }
        .accordion-header:hover { background: rgba(255,255,255,.05); }
        .accordion-header .acc-icon { font-size: 11px; transition: transform .2s; }
        .accordion-header.open .acc-icon { transform: rotate(180deg); }

        .accordion-content { padding: 12px 14px; display: none; background: rgba(0,0,0,.25); }
        .accordion-content.active { display: block; }

        /* ── Field groups ── */
        .field-group { margin-bottom: 12px; }
        .field-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: var(--ink-muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .color-row {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(0,0,0,.3);
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 5px 8px;
            transition: border-color var(--transition);
        }
        .color-row:focus-within { border-color: var(--gold); }

        .color-row input[type="color"] {
            width: 32px; height: 32px;
            border: none; padding: 0;
            border-radius: 5px;
            cursor: pointer;
            background: transparent;
            flex-shrink: 0;
        }
        .color-row input[type="text"] {
            flex: 1;
            background: transparent;
            color: var(--ink);
            border: none;
            outline: none;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            letter-spacing: .05em;
        }
        .color-swatch {
            width: 20px; height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,.1);
            flex-shrink: 0;
        }

        /* ── Background trigger ── */
        .bg-trigger-row {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
            padding: 10px 12px;
            background: rgba(201,162,39,.06);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }
        .bg-trigger-info {
            flex: 1;
            font-size: 12px;
            color: var(--ink-muted);
            line-height: 1.5;
        }
        .bg-trigger-info strong { color: var(--gold); display: block; margin-bottom: 2px; }

        /* ── Buttons ── */
        .config-footer {
            padding: 14px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 8px;
            flex-direction: column;
        }
        .btn {
            padding: 10px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            font-family: inherit;
            transition: all var(--transition);
        }
        .btn-gold { background: var(--gold); color: #000; }
        .btn-gold:hover { opacity: .9; }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--gold);
            color: var(--gold);
        }
        .btn-outline:hover { background: var(--gold-dim); }

        .btn-gen-bg {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--gold), #a8780d);
            color: #000;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            transition: all .2s;
        }
        .btn-gen-bg:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .btn-gen-bg:active { transform: translateY(0); }

        /* ── Preview area ── */
        .preview-area {
            flex: 1;
            background: #000;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 10px;
            padding-top: 52px;
        }
        .preview-header {
            position: absolute;
            top: 0; left: 0; right: 0;
            padding: 10px 18px;
            background: rgba(0,0,0,.6);
            backdrop-filter: blur(6px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 5;
            border-bottom: 1px solid var(--border);
        }

        #preview-wrapper {
            flex: 1;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,.6);
            overflow: hidden;
            border: 1px solid var(--border);
        }
        #live-preview-iframe { width: 100%; height: 100%; border: none; }

        /* ── Loader ── */
        .loader-overlay {
            position: fixed; top:0; left:0; right:0; bottom:0;
            background: rgba(0,0,0,.75);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            color: var(--gold);
            flex-direction: column;
            gap: 14px;
            backdrop-filter: blur(4px);
        }
        .spinner {
            width: 44px; height: 44px;
            border: 4px solid rgba(255,255,255,.08);
            border-top: 4px solid var(--gold);
            border-radius: 50%;
            animation: spin .9s linear infinite;
        }
        .loader-text { font-size: 14px; font-weight: 600; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Toast ── */
        #gen-toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: linear-gradient(135deg, #1a1a1a, #222);
            border: 1px solid var(--gold);
            color: var(--ink);
            padding: 12px 22px;
            border-radius: 40px;
            font-size: 13px;
            font-weight: 600;
            z-index: 9998;
            transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .3s;
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
            box-shadow: 0 8px 30px rgba(0,0,0,.5);
        }
        #gen-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        /* ── Meta fields ── */
        .meta-field {
            width: 100%;
            padding: 9px 11px;
            background: rgba(0,0,0,.4);
            border: 1px solid var(--border);
            color: var(--ink);
            border-radius: 7px;
            font-family: inherit;
            font-size: 13px;
            outline: none;
            transition: border-color var(--transition);
        }
        .meta-field:focus { border-color: var(--gold); }

        /* ── Mode toggle ── */
        .mode-pills {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .mode-pill {
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(255,255,255,.06);
            border: 1px solid var(--border);
            color: var(--ink-muted);
            font-size: 12px;
            cursor: pointer;
            transition: all var(--transition);
        }
        .mode-pill input[type="radio"] { display: none; }
        .mode-pill.active {
            background: var(--gold-dim);
            border-color: var(--gold);
            color: var(--gold);
        }
    </style>
</head>
<body>

@php
    $themeData = isset($theme->id) ? $theme : new \App\Models\Theme();
    $colors = is_array($themeData->colors)
        ? $themeData->colors
        : (json_decode($themeData->colors, true) ?? config('theme.defaults', []));

    // Fill missing keys from defaults
    $defaults = config('theme.defaults', []);
    foreach ($defaults as $k => $v) {
        if (empty($colors[$k])) {
            $colors[$k] = $v;
        }
    }

    // Sections for the accordion
    $sections = [
        'الألوان الرئيسية' => [
            'icon' => 'bi-palette',
            'keys' => ['primary', 'secondary', 'accent'],
        ],
        'الخلفيات والأسطح' => [
            'icon' => 'bi-layers',
            'keys' => ['surface'],
        ],
        'النصوص والعناوين' => [
            'icon' => 'bi-fonts',
            'keys' => ['text_primary', 'text_secondary', 'heading'],
        ],
        'الحدود والإطارات' => [
            'icon' => 'bi-border-style',
            'keys' => ['border'],
        ],
        'الأزرار' => [
            'icon' => 'bi-hand-index',
            'keys' => ['btn_primary', 'btn_secondary', 'btn_outline', 'btn_hover', 'btn_active', 'btn_disabled', 'btn_text'],
        ],
        'الروابط' => [
            'icon' => 'bi-link-45deg',
            'keys' => ['link_normal', 'link_hover', 'link_active'],
        ],
        'شريط التنقل (Navbar)' => [
            'icon' => 'bi-layout-text-window',
            'keys' => ['navbar_bg', 'navbar_text'],
        ],
        'الشريط الجانبي (Sidebar)' => [
            'icon' => 'bi-layout-sidebar',
            'keys' => ['sidebar_bg', 'sidebar_text', 'sidebar_active', 'sidebar_hover'],
        ],
        'التذييل (Footer)' => [
            'icon' => 'bi-layout-text-window-reverse',
            'keys' => ['footer_bg', 'footer_text', 'footer_links'],
        ],
        'حقول الإدخال' => [
            'icon' => 'bi-input-cursor-text',
            'keys' => ['input_bg', 'input_border', 'input_focus_border', 'input_placeholder', 'input_label'],
        ],
        'حالات النظام' => [
            'icon' => 'bi-check-circle',
            'keys' => ['success', 'warning', 'danger', 'info'],
        ],
    ];

    // Arabic label map
    $labels = [
        'primary'            => 'اللون الأساسي (Primary)',
        'secondary'          => 'اللون الثانوي (Secondary)',
        'accent'             => 'لون التمييز (Accent)',
        'surface'            => 'سطح البطاقات (Surface)',
        'text_primary'       => 'النص الرئيسي',
        'text_secondary'     => 'النص الثانوي',
        'heading'            => 'العناوين',
        'border'             => 'الحدود',
        'success'            => 'نجاح (Success)',
        'warning'            => 'تحذير (Warning)',
        'danger'             => 'خطر (Danger)',
        'info'               => 'معلومة (Info)',
        'btn_primary'        => 'زر أساسي',
        'btn_secondary'      => 'زر ثانوي',
        'btn_outline'        => 'زر Outline',
        'btn_hover'          => 'Hover الزر',
        'btn_active'         => 'Active الزر',
        'btn_disabled'       => 'Disabled الزر',
        'btn_text'           => 'نص الزر',
        'link_normal'        => 'رابط عادي',
        'link_hover'         => 'رابط Hover',
        'link_active'        => 'رابط Active',
        'input_bg'           => 'خلفية الحقل',
        'input_border'       => 'حدود الحقل',
        'input_focus_border' => 'حدود Focus',
        'input_placeholder'  => 'Placeholder',
        'input_label'        => 'تسمية الحقل',
        'navbar_bg'          => 'خلفية Navbar',
        'navbar_text'        => 'نص Navbar',
        'sidebar_bg'         => 'خلفية Sidebar',
        'sidebar_text'       => 'نص Sidebar',
        'sidebar_active'     => 'عنصر Active',
        'sidebar_hover'      => 'Hover Sidebar',
        'footer_bg'          => 'خلفية Footer',
        'footer_text'        => 'نص Footer',
        'footer_links'       => 'روابط Footer',
    ];
@endphp

<!-- Loader -->
<div id="loader" class="loader-overlay">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-text">جاري توليد الألوان...</div>
</div>

<!-- Toast -->
<div id="gen-toast">
    <i class="fas fa-magic" style="color: var(--gold); margin-left: 6px;"></i>
    <span id="gen-toast-msg">تم توليد الألوان من الخلفية!</span>
</div>

<form
    action="{{ isset($theme->id) ? route('admin.themes.update', $theme) : route('admin.themes.store') }}"
    method="POST"
    id="theme-form"
    class="builder-container"
>
    @csrf
    @if(isset($theme->id)) @method('PUT') @endif

    <!-- ════════════════════════════
         Sidebar
    ════════════════════════════ -->
    <div class="config-sidebar">

        <div class="config-header">
            <h2>
                <i class="bi bi-palette2" style="color: var(--gold); margin-left: 6px;"></i>
                {{ isset($theme->id) ? 'تعديل القالب' : 'قالب جديد' }}
            </h2>
            <a href="{{ route('admin.themes.index') }}" class="btn-close-x" title="إغلاق">
                <i class="fas fa-times"></i>
            </a>
        </div>

        <div class="config-body">

            <!-- Meta -->
            <div class="field-group">
                <label>اسم القالب</label>
                <input
                    type="text"
                    name="name"
                    value="{{ $themeData->name ?? '' }}"
                    required
                    class="meta-field"
                    placeholder="مثال: ثيم الذهب الداكن"
                >
            </div>

            <div class="field-group" style="margin-bottom: 18px;">
                <label>وصف (اختياري)</label>
                <textarea
                    name="description"
                    rows="2"
                    class="meta-field"
                    placeholder="وصف مختصر للقالب..."
                >{{ $themeData->description ?? '' }}</textarea>
            </div>

            <!-- ═══════════════════════
                 BACKGROUND — Main Driver
            ═══════════════════════ -->
            <div style="margin-bottom: 16px;">
                <div class="field-group" style="margin-bottom: 10px;">
                    <label>
                        <i class="bi bi-circle-half" style="color: var(--gold);"></i>
                        لون الخلفية الرئيسي — <span style="color: var(--gold);">المحرك الأساسي للهوية البصرية</span>
                    </label>
                    <div class="color-row" id="row-background">
                        <input
                            type="color"
                            data-key="background"
                            class="color-input"
                            id="color-background"
                            value="{{ $colors['background'] ?? '#1A1A1A' }}"
                        >
                        <input
                            type="text"
                            name="colors[background]"
                            id="txt-background"
                            value="{{ $colors['background'] ?? '#1A1A1A' }}"
                            maxlength="7"
                        >
                        <div class="color-swatch" id="swatch-background" style="background: {{ $colors['background'] ?? '#1A1A1A' }};"></div>
                    </div>
                </div>

                <div class="bg-trigger-row">
                    <div class="bg-trigger-info">
                        <strong><i class="fas fa-magic"></i> توليد ذكي من الخلفية</strong>
                        عند تغيير لون الخلفية، تُولَّد جميع ألوان الموقع تلقائياً لتتناسب معها — اللون الأساسي، الأزرار، النصوص، الـ Navbar، الـ Footer وكل شيء.
                    </div>
                    <button type="button" id="btn-gen-from-bg" class="btn-gen-bg" title="توليد الألوان من الخلفية">
                        <i class="fas fa-wand-magic-sparkles"></i>
                        توليد
                    </button>
                </div>
            </div>

            <!-- ═══════════════════════
                 Smart Generate from Primary (legacy)
            ═══════════════════════ -->
            <div style="margin-bottom: 14px;">
                <button type="button" id="btn-auto-generate" class="btn btn-outline" style="width: 100%; font-size: 12.5px;">
                    <i class="fas fa-magic"></i>
                    توليد ذكي من اللون الأساسي (Primary)
                </button>
            </div>

            <!-- ═══════════════════════
                 Accordion Color Sections
            ═══════════════════════ -->
            @foreach($sections as $title => $sectionData)
                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span>
                            <i class="bi {{ $sectionData['icon'] }}" style="margin-left: 6px; color: var(--gold);"></i>
                            {{ $title }}
                        </span>
                        <i class="fas fa-chevron-down acc-icon"></i>
                    </div>
                    <div class="accordion-content">
                        @foreach($sectionData['keys'] as $key)
                            @php $val = $colors[$key] ?? '#000000'; @endphp
                            <div class="field-group">
                                <label>{{ $labels[$key] ?? ucwords(str_replace('_', ' ', $key)) }}</label>
                                <div class="color-row" id="row-{{ $key }}">
                                    <input
                                        type="color"
                                        data-key="{{ $key }}"
                                        class="color-input"
                                        id="color-{{ $key }}"
                                        value="{{ $val }}"
                                    >
                                    <input
                                        type="text"
                                        name="colors[{{ $key }}]"
                                        id="txt-{{ $key }}"
                                        value="{{ $val }}"
                                        maxlength="7"
                                    >
                                    <div class="color-swatch" id="swatch-{{ $key }}" style="background: {{ $val }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div><!-- /config-body -->

        <div class="config-footer">
            <button type="submit" class="btn btn-gold">
                <i class="fas fa-save"></i> حفظ ونشر القالب
            </button>
            <button type="submit" name="save_draft" value="1" class="btn btn-outline">
                <i class="fas fa-file-alt"></i> حفظ كمسودة
            </button>
        </div>
    </div>

    <!-- ════════════════════════════
         Preview Area
    ════════════════════════════ -->
    <div class="preview-area">
        <div class="preview-header">
            <span style="color: var(--ink-muted); font-size: 13px;">
                <i class="fas fa-globe" style="color: var(--gold);"></i>
                معاينة حية للمتجر
            </span>
            <div style="display: flex; gap: 14px; align-items: center;">
                <button
                    type="button"
                    onclick="document.getElementById('live-preview-iframe').src='{{ url('/') }}'"
                    style="background: none; border: none; color: var(--ink-muted); cursor: pointer; font-size: 12px; font-family: inherit;"
                >
                    <i class="fas fa-sync"></i> تحديث الإطار
                </button>
                <div class="mode-pills">
                    <label class="mode-pill active" id="pill-dark">
                        <input type="radio" name="preview_mode" value="dark" checked>
                        🌙 داكن
                    </label>
                    <label class="mode-pill" id="pill-light">
                        <input type="radio" name="preview_mode" value="light">
                        ☀️ فاتح
                    </label>
                </div>
            </div>
        </div>

        <div id="preview-wrapper">
            <iframe
                id="live-preview-iframe"
                src="{{ url('/') }}"
                sandbox="allow-same-origin allow-scripts allow-forms allow-popups"
            ></iframe>
        </div>
    </div>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Elements ────────────────────────────────────────────────
    const form        = document.getElementById('theme-form');
    const iframe      = document.getElementById('live-preview-iframe');
    const loader      = document.getElementById('loader');
    const loaderText  = document.getElementById('loader-text');
    const toast       = document.getElementById('gen-toast');
    const toastMsg    = document.getElementById('gen-toast-msg');
    const autoGenBtn  = document.getElementById('btn-auto-generate');
    const genBgBtn    = document.getElementById('btn-gen-from-bg');
    const bgColorInput = document.getElementById('color-background');
    const bgTextInput  = document.getElementById('txt-background');
    const LIVE_PALETTE_KEY = 'ha_live_theme_palette';
    const LIVE_MODE_KEY = 'ha_live_theme_mode';
    let lastSubmitter = null;

    // ─── Full CSS variable map ───────────────────────────────────
    // Maps our internal key → every CSS custom property that key controls
    const CSS_MAP = {
        primary:            ['--primary',          '--primary-color'],
        secondary:          ['--secondary',         '--secondary-color'],
        accent:             ['--accent',            '--accent-color'],
        background:         ['--background',        '--bg-color'],
        surface:            ['--surface',           '--surface-color', '--card-bg', '--section-bg', '--modal-bg', '--table-bg'],
        text_primary:       ['--text-primary',      '--text-color',    '--input-text'],
        text_secondary:     ['--text-secondary',    '--text-muted'],
        heading:            ['--heading',           '--heading-color'],
        border:             ['--border',            '--border-color',  '--scrollbar-thumb'],
        success:            ['--success',           '--success-color'],
        warning:            ['--warning',           '--warning-color'],
        danger:             ['--danger',            '--danger-color'],
        info:               ['--info',              '--info-color'],
        btn_primary:        ['--btn-primary',       '--btn-primary-bg'],
        btn_secondary:      ['--btn-secondary'],
        btn_outline:        ['--btn-outline',       '--btn-outline-color'],
        btn_hover:          ['--btn-hover',         '--btn-hover-bg',  '--hover-color'],
        btn_active:         ['--btn-active',        '--btn-active-bg'],
        btn_disabled:       ['--btn-disabled',      '--btn-disabled-bg'],
        btn_text:           ['--btn-text',          '--btn-text-color'],
        link_normal:        ['--link-normal'],
        link_hover:         ['--link-hover'],
        link_active:        ['--link-active'],
        input_bg:           ['--input-bg'],
        input_border:       ['--input-border'],
        input_focus_border: ['--input-focus-border'],
        input_placeholder:  ['--input-placeholder'],
        input_label:        ['--input-label'],
        navbar_bg:          ['--navbar-bg'],
        navbar_text:        ['--navbar-text'],
        sidebar_bg:         ['--sidebar-bg'],
        sidebar_text:       ['--sidebar-text'],
        sidebar_active:     ['--sidebar-active'],
        sidebar_hover:      ['--sidebar-hover'],
        footer_bg:          ['--footer-bg'],
        footer_text:        ['--footer-text'],
        footer_links:       ['--footer-links'],
    };

    // Current color state
    const currentColors = {};

    // ─── Iframe helpers ──────────────────────────────────────────
    function getIframeDoc() {
        try { return iframe.contentDocument || iframe.contentWindow.document; }
        catch (e) { return null; }
    }

    function applyColorToIframe(key, value) {
        const doc = getIframeDoc();
        if (!doc || !doc.documentElement) return;
        const vars = CSS_MAP[key] || ['--' + key];
        vars.forEach(v => doc.documentElement.style.setProperty(v, value));
    }

    function applyAllToIframe() {
        const doc = getIframeDoc();
        if (!doc || !doc.documentElement) return;
        for (const [key, val] of Object.entries(currentColors)) {
            applyColorToIframe(key, val);
        }
        const mode = document.querySelector('input[name="preview_mode"]:checked').value;
        doc.documentElement.setAttribute('data-theme', mode);
        syncPreviewState();
    }

   function syncPreviewState() {
        const mode = document.querySelector('input[name="preview_mode"]:checked').value;
        sessionStorage.setItem(LIVE_PALETTE_KEY, JSON.stringify(currentColors));
        sessionStorage.setItem(LIVE_MODE_KEY, mode);
        window.dispatchEvent(new CustomEvent('ha-preview-theme-change', {
            detail: {
                colors: currentColors,
                mode: mode,
            }
        }));
    }

    function clearPreviewState() {
        sessionStorage.removeItem(LIVE_PALETTE_KEY);
        sessionStorage.removeItem(LIVE_MODE_KEY);
        window.dispatchEvent(new CustomEvent('ha-preview-theme-change', {
            detail: {
                colors: null,
                mode: localStorage.getItem('ha_theme') || 'dark',
            }
        }));
        if (window.HaLiveTheme && typeof window.HaLiveTheme.clear === 'function') {
            window.HaLiveTheme.clear();
        }
    }

localStorage.removeItem('ha_live_theme_mode');


    // ─── UI update ───────────────────────────────────────────────
    function updateColor(key, value, skipIframe = false) {
        currentColors[key] = value;

        // Sync text input
        const txt = document.getElementById('txt-' + key);
        if (txt && txt.value !== value) txt.value = value;

        // Sync color picker
        const picker = document.getElementById('color-' + key);
        if (picker && picker.value !== value) picker.value = value;

        // Sync swatch
        const swatch = document.getElementById('swatch-' + key);
        if (swatch) swatch.style.background = value;

        if (!skipIframe) applyColorToIframe(key, value);
        syncPreviewState();
    }

    // Apply a whole palette (from API response)
    function applyPalette(data) {
        for (const [key, value] of Object.entries(data)) {
            if (typeof value === 'string' && value.startsWith('#')) {
                updateColor(key, value);
            }
        }
        applyAllToIframe();
    }

    // ─── Toast ───────────────────────────────────────────────────
    let toastTimer = null;
    function showToast(msg) {
        toastMsg.textContent = msg;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 3500);
    }

    // ─── Loader ──────────────────────────────────────────────────
    function showLoader(text) {
        loaderText.textContent = text || 'جاري توليد الألوان...';
        loader.style.display = 'flex';
    }
    function hideLoader() { loader.style.display = 'none'; }

    // ─── CORE: Generate from Background ─────────────────────────
    function generateFromBackground(bgHex) {
        showLoader('🎨 جاري توليد ألوان الهوية البصرية من الخلفية...');

        fetch('{{ route('admin.themes.generate.background') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ background: bgHex })
        })
        .then(r => r.json())
        .then(data => {
            hideLoader();
            if (data && !data.errors) {
                applyPalette(data);
                showToast('✅ تم توليد ' + Object.keys(data).length + ' لون من الخلفية تلقائياً!');
            } else {
                showToast('❌ حدث خطأ في التوليد.');
                console.error(data);
            }
        })
        .catch(err => {
            hideLoader();
            showToast('❌ تعذّر الاتصال بالخادم.');
            console.error(err);
        });
    }

    // ─── CORE: Generate from Primary ────────────────────────────
    function generateFromPrimary() {
        const primaryHex = currentColors['primary'] || '#D4AF37';
        const mode = document.querySelector('input[name="preview_mode"]:checked').value;

        showLoader('✨ جاري توليد الألوان من اللون الأساسي...');

        fetch('{{ route('admin.themes.generate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ primary: primaryHex, mode: mode })
        })
        .then(r => r.json())
        .then(data => {
            hideLoader();
            if (data) {
                applyPalette(data);
                showToast('✅ تم توليد الألوان من اللون الأساسي!');
            }
        })
        .catch(err => {
            hideLoader();
            showToast('❌ تعذّر الاتصال بالخادم.');
            console.error(err);
        });
    }

    // ─── Debounce helper ─────────────────────────────────────────
    function debounce(fn, delay) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
    }

    // ─── Bind all color inputs ───────────────────────────────────
    const debouncedBgGen = debounce(generateFromBackground, 900);

    document.querySelectorAll('.color-input').forEach(input => {
        const key = input.dataset.key;

        // Initialize state
        currentColors[key] = input.value;

        // Color picker change
        input.addEventListener('input', function (e) {
            updateColor(key, e.target.value);

            // Auto-generate when background changes
            if (key === 'background') {
                debouncedBgGen(e.target.value);
            }
        });

        // Text input change
        const txt = document.getElementById('txt-' + key);
        if (txt) {
            txt.addEventListener('input', function (e) {
                const val = e.target.value.trim();
                if (/^#[0-9A-Fa-f]{6}$/i.test(val)) {
                    updateColor(key, val);
                    if (key === 'background') {
                        debouncedBgGen(val);
                    }
                }
            });
        }
    });

    // ─── Manual generate buttons ─────────────────────────────────
    genBgBtn.addEventListener('click', function () {
        const bgHex = currentColors['background'] || '#1A1A1A';
        generateFromBackground(bgHex);
    });

    autoGenBtn.addEventListener('click', generateFromPrimary);

    // ─── Mode toggle ─────────────────────────────────────────────
    const darkPill  = document.getElementById('pill-dark');
    const lightPill = document.getElementById('pill-light');

    document.querySelectorAll('input[name="preview_mode"]').forEach(r => {
        r.addEventListener('change', function () {
            darkPill.classList.toggle('active',  this.value === 'dark');
            lightPill.classList.toggle('active', this.value === 'light');

            const doc = getIframeDoc();
            if (doc && doc.documentElement) {
                doc.documentElement.setAttribute('data-theme', this.value);
            }
            syncPreviewState();
        });
    });

    // ─── Re-inject colors after iframe navigation ────────────────
    iframe.addEventListener('load', applyAllToIframe);

    // ─── Accordion toggle ─────────────────────────────────────────
    window.toggleAccordion = function (header) {
        const content = header.nextElementSibling;
        const isOpen  = content.classList.contains('active');

        // Close all
        document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.accordion-header').forEach(h => h.classList.remove('open'));

        if (!isOpen) {
            content.classList.add('active');
            header.classList.add('open');
        }
    };

    // Auto-open first accordion
    const firstHeader = document.querySelector('.accordion-header');
    if (firstHeader) toggleAccordion(firstHeader);

    if (form) {
        form.addEventListener('click', function (event) {
            if (event.target instanceof HTMLButtonElement && event.target.type === 'submit') {
                lastSubmitter = event.target;
            }
        });

        form.addEventListener('submit', function () {
            const isDraft = lastSubmitter && lastSubmitter.name === 'save_draft';
            if (isDraft) {
                clearPreviewState();
            } else {
                syncPreviewState();
            }
        });
    }

    window.addEventListener('beforeunload', function () {
        if (!lastSubmitter) {
            clearPreviewState();
        }
    });

    syncPreviewState();

});
</script>
</body>
</html>
