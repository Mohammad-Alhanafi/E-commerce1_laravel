{{--
    ============================================================
    components/theme-head.blade.php  — v3.0 FULL COVERAGE
    ────────────────────────────────────────────────────────────
    • Injects ALL CSS Custom Properties used in theme.css
    • Covers: backgrounds, typography, borders, brand palette,
      status, buttons, links, forms/inputs, navbar, footer,
      sidebar, tables, modals, dropdowns, scrollbar, skeletons
    • Also overrides SweetAlert2 popup colors at runtime via JS
    • Anti-FOUC inline script for instant theme loading
    ============================================================
--}}

@php
    $locale = app()->getLocale();
    $isRtl  = ($locale === 'ar');
    $c      = $themeColors ?? \App\Models\Theme::getActiveColors();
    $themeCssMap = config('theme.css_map', []);
    $themeLegacyMap = config('theme.legacy_css_map', []);
    $runtimeThemeMap = [];
    foreach ($themeCssMap as $key => $value) {
        $runtimeThemeMap[$key] = [$value];
    }
    foreach ($themeLegacyMap as $key => $value) {
        $runtimeThemeMap[$key] = array_values(array_unique(array_merge($runtimeThemeMap[$key] ?? [], [$value])));
    }
    $runtimeThemeMap['primary'] = array_values(array_unique(array_merge($runtimeThemeMap['primary'] ?? [], ['--primary-color', '--primary-gold', '--gold-primary'])));
    $runtimeThemeMap['secondary'] = array_values(array_unique(array_merge($runtimeThemeMap['secondary'] ?? [], ['--secondary-color', '--secondary-gold'])));
    $runtimeThemeMap['accent'] = array_values(array_unique(array_merge($runtimeThemeMap['accent'] ?? [], ['--accent-color'])));
    $runtimeThemeMap['background'] = array_values(array_unique(array_merge($runtimeThemeMap['background'] ?? [], ['--bg-color', '--main-bg', '--primary-black'])));
    $runtimeThemeMap['surface'] = array_values(array_unique(array_merge($runtimeThemeMap['surface'] ?? [], ['--section-bg', '--card-bg', '--modal-bg', '--table-bg', '--surface-bg', '--card-black', '--light-black', '--grey-black', '--surface-color'])));
    $runtimeThemeMap['text_primary'] = array_values(array_unique(array_merge($runtimeThemeMap['text_primary'] ?? [], ['--text-color', '--input-text', '--white', '--heading-color'])));
    $runtimeThemeMap['text_secondary'] = array_values(array_unique(array_merge($runtimeThemeMap['text_secondary'] ?? [], ['--text-muted'])));
    $runtimeThemeMap['heading'] = array_values(array_unique(array_merge($runtimeThemeMap['heading'] ?? [], ['--heading-color'])));
    $runtimeThemeMap['border'] = array_values(array_unique(array_merge($runtimeThemeMap['border'] ?? [], ['--border-color', '--scrollbar-thumb'])));

    /* helpers — derive shadow & skeleton from background */
    $bg       = $c['background']     ?? '#1A1A1A';
    $surface  = $c['surface']        ?? '#111111';
    $primary  = $c['primary']        ?? '#D4AF37';
    $secondary= $c['secondary']      ?? '#B8941E';
    $border   = $c['border']         ?? '#3A3A3A';
    $txtPri   = $c['text_primary']   ?? '#FFFFFF';
    $txtSec   = $c['text_secondary'] ?? '#AAAAAA';
@endphp

{{-- ── 0. Resource Hints for CDNs ────────────────────────── --}}
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

{{-- ── 1. Bootstrap 5 (RTL / LTR) ────────────────────────── --}}
@if($isRtl)
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
@else
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
@endif

{{-- ── 2. Core theme.css (loaded ONCE here — do NOT load again elsewhere) ── --}}
<link rel="stylesheet" href="{{ asset('css/theme.css') }}">

{{-- ── 3. Dynamic theme variables — override every :root value ──────────── --}}
<style id="ha-dynamic-theme-vars">

/* ================================================================
   DARK MODE  (default / [data-theme="dark"])
   ================================================================ */
:root,
[data-theme="dark"] {

    /* ─── Brand ─── */
    --primary-color:       {{ $primary }};
    --secondary-color:     {{ $secondary }};
    --hover-color:         {{ $c['btn_hover']           ?? '#C89B2C' }};
    --accent-color:        {{ $c['accent']              ?? '#E8C547' }};

    /* ─── Backgrounds ─── */
    --bg-color:            {{ $bg }};
    --section-bg:          {{ $surface }};
    --card-bg:             {{ $surface }};
    --modal-bg:            {{ $surface }};
    --table-bg:            {{ $bg }};
    --input-bg:            {{ $c['input_bg']            ?? '#222222' }};
    --dropdown-bg:         {{ $surface }};
    --navbar-bg:           {{ $c['navbar_bg']           ?? '#111111' }};
    --footer-bg:           {{ $c['footer_bg']           ?? '#111111' }};
    --sidebar-bg:          {{ $c['sidebar_bg']          ?? '#111111' }};
    --skeleton-bg:         {{ $surface }};
    --skeleton-shine:      {{ $border }};
    --scrollbar-track:     {{ $bg }};
    --scrollbar-thumb:     {{ $border }};
    --scrollbar-hover:     {{ $primary }};

    /* ─── Typography ─── */
    --text-color:          {{ $txtPri }};
    --text-muted:          {{ $txtSec }};
    --input-text:          {{ $txtPri }};
    --navbar-text:         {{ $c['navbar_text']         ?? '#FFFFFF' }};
    --footer-text:         {{ $c['footer_text']         ?? '#AAAAAA' }};
    --footer-links:        {{ $c['footer_links']        ?? $primary }};
    --sidebar-text:        {{ $c['sidebar_text']        ?? '#AAAAAA' }};
    --sidebar-active:      {{ $c['sidebar_active']      ?? $primary }};
    --sidebar-hover:       {{ $c['sidebar_hover']       ?? '#2C2C2C' }};

    /* ─── Borders & Shadows ─── */
    --border-color:        {{ $border }};
    --shadow-color:        rgba(0,0,0,0.6);

    /* ─── Status ─── */
    --success-color:       {{ $c['success']             ?? '#28A745' }};
    --danger-color:        {{ $c['danger']              ?? '#DC3545' }};
    --warning-color:       {{ $c['warning']             ?? '#FFC107' }};
    --info-color:          {{ $c['info']                ?? '#17A2B8' }};

    /* ─── Order Status Colors (حالات الطلبات) ─── */
    --order-pending-color:     {{ $c['order_pending']    ?? $c['warning']  ?? '#FFC107' }};
    --order-processing-color:  {{ $c['order_processing'] ?? $c['info']     ?? '#17A2B8' }};
    --order-completed-color:   {{ $c['order_completed']  ?? $c['success']  ?? '#28A745' }};
    --order-shipped-color:     {{ $c['order_shipped']    ?? $primary       ?? '#D4AF37' }};
    --order-canceled-color:    {{ $c['order_canceled']   ?? $c['danger']   ?? '#DC3545' }};

    /* ─── Buttons ─── */
    --btn-primary-bg:      {{ $c['btn_primary']         ?? $primary }};
    --btn-secondary-bg:    {{ $c['btn_secondary']       ?? $secondary }};
    --btn-outline-color:   {{ $c['btn_outline']         ?? $primary }};
    --btn-hover-bg:        {{ $c['btn_hover']           ?? '#C89B2C' }};
    --btn-active-bg:       {{ $c['btn_active']          ?? '#A8841A' }};
    --btn-disabled-bg:     {{ $c['btn_disabled']        ?? '#555555' }};
    --btn-text-color:      {{ $c['btn_text']            ?? '#000000' }};

    /* ─── Links ─── */
    --link-normal:         {{ $c['link_normal']         ?? $primary }};
    --link-hover:          {{ $c['link_hover']          ?? '#E8C547' }};
    --link-active:         {{ $c['link_active']         ?? $secondary }};

    /* ─── Inputs / Forms ─── */
    --input-border:        {{ $c['input_border']        ?? $border }};
    --input-focus-border:  {{ $c['input_focus_border']  ?? $primary }};
    --input-placeholder:   {{ $c['input_placeholder']   ?? '#666666' }};
    --input-label:         {{ $c['input_label']         ?? '#CCCCCC' }};

    /* ─── Legacy & Utility Aliases (For total site-wide dynamic binding) ─── */
    --primary-gold:        {{ $primary }};
    --gold-primary:        {{ $primary }};
    --secondary-gold:      {{ $secondary }};
    --primary-black:       {{ $bg }};
    --card-black:          {{ $surface }};
    --light-black:         {{ $surface }};
    --grey-black:          {{ $surface }};
    --main-bg:             {{ $bg }};
    --surface-bg:          {{ $surface }};
    --white:               {{ $txtPri }};
    --primary:             {{ $primary }};
    --secondary:           {{ $secondary }};
    --background:          {{ $bg }};
    --surface:             {{ $surface }};
    --border:              {{ $border }};
    --heading:             {{ $c['heading']             ?? $txtPri }};
    --text-primary:        {{ $txtPri }};
    --text-secondary:      {{ $txtSec }};
    --surface-color:       {{ $surface }};
    --heading-color:       {{ $c['heading']             ?? $txtPri }};
    --overlay-bg:          rgba(0,0,0,0.75);
    --badge-bg:            {{ $surface }};
    --theme-transition:    0.3s ease;
}

/* ================================================================
   LIGHT MODE  [data-theme="light"]
   ================================================================ */
[data-theme="light"] {

    /* ─── Brand (unchanged — same gold palette) ─── */
    --primary-color:       {{ $primary }};
    --secondary-color:     {{ $secondary }};
    --hover-color:         {{ $c['btn_hover']           ?? '#C89B2C' }};
    --accent-color:        {{ $c['accent']              ?? '#E8C547' }};

    /* ─── Legacy & Utility Aliases for Light Mode ─── */
    --primary-gold:        {{ $primary }};
    --gold-primary:        {{ $primary }};
    --secondary-gold:      {{ $secondary }};
    --primary-black:       #F8F9FA;
    --card-black:          #FFFFFF;
    --light-black:         #FFFFFF;
    --grey-black:          #F1F3F5;
    --main-bg:             #F8F9FA;
    --surface-bg:          #FFFFFF;
    --white:               #212529;
    --primary:             {{ $primary }};
    --secondary:           {{ $secondary }};
    --background:          #F8F9FA;
    --surface:             #FFFFFF;
    --border:              #DEE2E6;
    --heading:             #212529;
    --text-primary:        #212529;
    --text-secondary:      #6C757D;

    /* ─── Backgrounds ─── */
    --bg-color:            #F8F9FA;
    --section-bg:          #FFFFFF;
    --card-bg:             #FFFFFF;
    --modal-bg:            #FFFFFF;
    --table-bg:            #FFFFFF;
    --input-bg:            #FFFFFF;
    --dropdown-bg:         #FFFFFF;
    --navbar-bg:           {{ ($c['navbar_bg'] ?? '#111111') !== '#111111' ? ($c['navbar_bg'] ?? '#FFFFFF') : '#FFFFFF' }};
    --footer-bg:           {{ ($c['footer_bg'] ?? '#111111') !== '#111111' ? ($c['footer_bg'] ?? '#F1F3F5') : '#F1F3F5' }};
    --sidebar-bg:          #FFFFFF;
    --skeleton-bg:         #E9ECEF;
    --skeleton-shine:      #F8F9FA;
    --scrollbar-track:     #F1F3F5;
    --scrollbar-thumb:     #DEE2E6;
    --scrollbar-hover:     {{ $primary }};

    /* ─── Typography ─── */
    --text-color:          #212529;
    --text-muted:          #6C757D;
    --input-text:          #212529;
    --navbar-text:         #212529;
    --footer-text:         #6C757D;
    --sidebar-text:        #6C757D;
    --sidebar-hover:       #F1F3F5;

    /* ─── Borders & Shadows ─── */
    --border-color:        #DEE2E6;
    --shadow-color:        rgba(0,0,0,0.10);
    --input-border:        #DEE2E6;

    /* ─── Status (same) ─── */
    --success-color:       {{ $c['success']             ?? '#28A745' }};
    --danger-color:        {{ $c['danger']              ?? '#DC3545' }};
    --warning-color:       {{ $c['warning']             ?? '#FFC107' }};
    --info-color:          {{ $c['info']                ?? '#17A2B8' }};

    /* ─── Order Status Colors (حالات الطلبات) ─── */
    --order-pending-color:     {{ $c['order_pending']    ?? $c['warning']  ?? '#FFC107' }};
    --order-processing-color:  {{ $c['order_processing'] ?? $c['info']     ?? '#17A2B8' }};
    --order-completed-color:   {{ $c['order_completed']  ?? $c['success']  ?? '#28A745' }};
    --order-shipped-color:     {{ $c['order_shipped']    ?? $primary       ?? '#D4AF37' }};
    --order-canceled-color:    {{ $c['order_canceled']   ?? $c['danger']   ?? '#DC3545' }};

    /* ─── Inputs ─── */
    --input-placeholder:   #ADB5BD;
    --input-label:         #495057;
    --overlay-bg:          rgba(0,0,0,0.40);
    --badge-bg:            #E9ECEF;
}

/* ================================================================
   GLOBAL COMPONENT OVERRIDES (apply on top of Bootstrap / theme.css)
   ================================================================ */

/* ── Forms (Complete Dynamic System) ── */
.form-control, .form-select,
input[type="text"], input[type="email"], input[type="password"],
input[type="number"], input[type="search"], input[type="tel"],
input[type="url"], input[type="date"], input[type="time"], input[type="datetime-local"],
textarea, select {
    background-color: var(--input-bg) !important;
    color:            var(--input-text) !important;
    border-color:     var(--input-border) !important;
    transition:       border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease !important;
}
.form-control:focus, input:focus, textarea:focus, select:focus {
    background-color: var(--input-bg) !important;
    border-color:     var(--input-focus-border, var(--primary-color)) !important;
    box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary-color) 22%, transparent) !important;
    color: var(--input-text) !important;
    outline: none !important;
}
::placeholder, .form-control::placeholder { color: var(--input-placeholder) !important; opacity: 0.8; }
label, .form-label, legend, .form-check-label { color: var(--input-label, var(--text-color)) !important; font-weight: 500; }

/* Checkboxes & Radios */
.form-check-input, input[type="checkbox"], input[type="radio"] {
    background-color: var(--input-bg) !important;
    border-color: var(--input-border, var(--border-color)) !important;
    cursor: pointer;
}
.form-check-input:checked, input[type="checkbox"]:checked, input[type="radio"]:checked {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}
.form-check-input:focus, input[type="checkbox"]:focus, input[type="radio"]:focus {
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 25%, transparent) !important;
}

/* Input Groups & Select Options */
.input-group-text {
    background-color: color-mix(in srgb, var(--primary-color) 10%, var(--card-bg)) !important;
    color: var(--primary-color) !important;
    border-color: var(--input-border, var(--border-color)) !important;
}
select option {
    background-color: var(--card-bg, #111111) !important;
    color: var(--text-color, #ffffff) !important;
}

/* ── Tables (Complete Internal Dynamic System) ── */
.table, table, .table-responsive {
    background-color: var(--table-bg, var(--card-bg)) !important;
    color: var(--text-color) !important;
    border-color: var(--border-color) !important;
}
.table th, .table td, th, td {
    color: var(--text-color) !important;
    border-color: var(--border-color) !important;
    vertical-align: middle;
}
.table thead th, thead th {
    background-color: color-mix(in srgb, var(--primary-color) 12%, var(--section-bg, var(--card-bg))) !important;
    color: var(--primary-color) !important;
    font-weight: 700 !important;
    border-bottom: 2px solid var(--primary-color) !important;
    letter-spacing: 0.3px;
}
.table-striped > tbody > tr:nth-of-type(odd),
.table tbody tr:nth-child(even) {
    background-color: color-mix(in srgb, var(--primary-color) 3%, var(--card-bg)) !important;
}
/* Disable all table row and cell hover effects globally */
.table tbody tr:hover,
.table tbody tr:hover > td,
.table tbody tr:hover > th,
.table thead tr:hover,
.table thead tr th:hover,
.table-hover > tbody > tr:hover,
.table-hover > tbody > tr:hover > *,
.table tbody tr:hover > *,
table tbody tr:hover,
table tbody tr:hover > td,
table tbody tr:hover > th,
table th:hover,
table td:hover {
    background-color: inherit !important;
    color: inherit !important;
    box-shadow: none !important;
    transform: none !important;
    filter: none !important;
    transition: none !important;
    text-shadow: none !important;
}
.table tfoot th, .table tfoot td {
    background-color: var(--section-bg) !important;
    color: var(--text-color) !important;
    border-top: 2px solid var(--border-color) !important;
}

/* ── Links ── */
a:not(.btn):not(.nav-link):not(.dropdown-item):not(.user-item):not(.page-link) {
    color: var(--link-normal, var(--primary-color));
}
a:not(.btn):not(.nav-link):not(.dropdown-item):not(.user-item):not(.page-link):hover {
    color: var(--link-hover, var(--hover-color));
}

/* ── Status / Alert badges ── */
.alert-success  { background: color-mix(in srgb, var(--success-color) 12%, transparent) !important; border-color: color-mix(in srgb, var(--success-color) 35%, transparent) !important; color: var(--success-color) !important; }
.alert-danger   { background: color-mix(in srgb, var(--danger-color)  12%, transparent) !important; border-color: color-mix(in srgb, var(--danger-color)  35%, transparent) !important; color: var(--danger-color)  !important; }
.alert-warning  { background: color-mix(in srgb, var(--warning-color) 12%, transparent) !important; border-color: color-mix(in srgb, var(--warning-color) 35%, transparent) !important; color: var(--warning-color) !important; }
.alert-info     { background: color-mix(in srgb, var(--info-color)    12%, transparent) !important; border-color: color-mix(in srgb, var(--info-color)    35%, transparent) !important; color: var(--info-color)    !important; }
.badge.bg-success { background-color: var(--success-color) !important; }
.badge.bg-danger  { background-color: var(--danger-color)  !important; }
.badge.bg-warning { background-color: var(--warning-color) !important; color: #000 !important; }
.badge.bg-info    { background-color: var(--info-color)    !important; }

/* ── Buttons ── */
.btn-primary, .btn-gold {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    border-color: var(--primary-color) !important;
    color: var(--btn-text-color, #000) !important;
}
.btn-primary:hover, .btn-gold:hover {
    background: linear-gradient(135deg, var(--hover-color), var(--secondary-color)) !important;
    border-color: var(--hover-color) !important;
    color: var(--btn-text-color, #000) !important;
}
.btn-outline-primary { color: var(--primary-color) !important; border-color: var(--primary-color) !important; }
.btn-outline-primary:hover { background-color: var(--primary-color) !important; color: var(--btn-text-color, #000) !important; }
.btn-success { background-color: var(--success-color) !important; border-color: var(--success-color) !important; color: #fff !important; }
.btn-danger  { background-color: var(--danger-color)  !important; border-color: var(--danger-color)  !important; color: #fff !important; }
.btn-warning { background-color: var(--warning-color) !important; border-color: var(--warning-color) !important; color: #000 !important; }
.btn-info    { background-color: var(--info-color)    !important; border-color: var(--info-color)    !important; color: #fff !important; }

/* ── Bootstrap utilities ── */
.bg-primary    { background-color: var(--primary-color) !important; }
.bg-success    { background-color: var(--success-color) !important; }
.bg-danger     { background-color: var(--danger-color)  !important; }
.bg-warning    { background-color: var(--warning-color) !important; }
.bg-info       { background-color: var(--info-color)    !important; }
.text-primary  { color: var(--primary-color) !important; }
.text-success  { color: var(--success-color) !important; }
.text-danger   { color: var(--danger-color)  !important; }
.text-warning  { color: var(--warning-color) !important; }
.text-info     { color: var(--info-color)    !important; }
.text-muted    { color: var(--text-muted)    !important; }
.border-primary { border-color: var(--primary-color) !important; }

/* ── Cards / Surfaces ── */
.card, .card-custom { background-color: var(--card-bg) !important; border-color: var(--border-color) !important; }
.card-header, .card-footer { background-color: var(--section-bg) !important; border-color: var(--border-color) !important; }

/* ── Pagination ── */
.page-link { background-color: var(--card-bg); border-color: var(--border-color); color: var(--text-color); }
.page-link:hover { background-color: var(--primary-color); border-color: var(--primary-color); color: #000; }
.page-item.active .page-link { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; color: #000 !important; }
.page-item.disabled .page-link { background-color: var(--section-bg); border-color: var(--border-color); color: var(--text-muted); }

/* ── Dropdowns ── */
.dropdown-menu { background-color: var(--dropdown-bg) !important; border-color: var(--border-color) !important; }
.dropdown-item { color: var(--text-color) !important; }
.dropdown-item:hover { background-color: color-mix(in srgb, var(--primary-color) 12%, transparent) !important; color: var(--primary-color) !important; }
.dropdown-divider { border-color: var(--border-color) !important; }

/* ── Header / Navbar ── */
.site-header, header, .navbar, .main-header, #mainHeader {
    background-color: var(--navbar-bg) !important;
    color:            var(--navbar-text) !important;
    border-bottom: 1px solid var(--border-color) !important;
}
.site-header a, header a, .navbar a, .main-nav a, .nav-link, .logo-text {
    color: var(--navbar-text) !important;
}
.site-header a:hover, header a:hover, .navbar a:hover, .main-nav a:hover, .nav-link:hover {
    color: var(--primary-color) !important;
}

/* ── Footer ── */
.site-footer, footer, .footer-section, .footer-wrapper, #mainFooter {
    background-color: var(--footer-bg) !important;
    color:            var(--footer-text) !important;
    border-top: 1px solid var(--border-color) !important;
}
.site-footer a, footer a, .footer-links a {
    color: var(--footer-links, var(--primary-color)) !important;
}
.site-footer a:hover, footer a:hover, .footer-links a:hover {
    color: var(--link-hover, var(--hover-color)) !important;
}
.site-footer p, footer p, .site-footer span, footer span {
    color: var(--footer-text) !important;
}

/* ── Sidebar ── */
.sidebar, .admin-sidebar, .main-sidebar, aside, .sidebar-wrapper {
    background-color: var(--sidebar-bg) !important;
    color:            var(--sidebar-text) !important;
    border-color:     var(--border-color) !important;
}
.sidebar a, .admin-sidebar a, aside a, .sidebar-link {
    color: var(--sidebar-text) !important;
}
.sidebar a.active, .admin-sidebar a.active, aside a.active, .sidebar-link.active {
    color:            var(--sidebar-active, var(--primary-color)) !important;
    background-color: var(--sidebar-hover) !important;
}
.sidebar a:hover, .admin-sidebar a:hover, aside a:hover, .sidebar-link:hover {
    background-color: var(--sidebar-hover) !important;
    color:            var(--sidebar-active, var(--primary-color)) !important;
}

/* ── Cards, Products, Categories & Comments ── */
.card, .card-custom, .product-card, .category-card,
.comment-box, .comment-card, .comment-item, .reply-card,
.cart-card, .checkout-card, .account-card, .dashboard-card,
.stat-card, .waqar-comment-card, .chat-bubble, .edit-modal-content {
    background-color: var(--card-bg, var(--surface-color)) !important;
    border:           1px solid var(--border-color) !important;
    color:            var(--text-color) !important;
}
.product-title, .category-title, .comment-author, .card-title,
.waqar-com-name, .waqar-reply-name {
    color: var(--heading-color, var(--text-color)) !important;
}
.product-desc, .category-desc, .comment-text, .card-text,
.waqar-com-text, .waqar-com-time {
    color: var(--text-muted) !important;
}
.product-price {
    color: var(--primary-color) !important;
    font-weight: 700;
}

/* ── Comments System Full Coverage ── */
.waqar-comments {
    background-color: var(--bg-color) !important;
    color: var(--text-color) !important;
}
.waqar-avatar {
    background-color: var(--primary-color) !important;
    color: var(--btn-text-color, #000) !important;
}
.waqar-action-btn {
    border: 1px solid var(--border-color) !important;
    background-color: color-mix(in srgb, var(--primary-color) 10%, transparent) !important;
    color: var(--primary-color) !important;
}
.waqar-action-btn:hover {
    background-color: var(--primary-color) !important;
    color: var(--btn-text-color, #000) !important;
}
.waqar-action-btn.danger {
    color: var(--danger-color) !important;
    border-color: color-mix(in srgb, var(--danger-color) 30%, transparent) !important;
}
.waqar-action-btn.danger:hover {
    background-color: var(--danger-color) !important;
    color: #ffffff !important;
}
.comment-add-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    color: var(--btn-text-color, #000) !important;
}
.waqar-reply-box textarea, .edit-textarea, #newComment, #editText {
    background-color: var(--input-bg) !important;
    color: var(--input-text) !important;
    border: 1px solid var(--input-border) !important;
}
.waqar-reply-send {
    background-color: var(--primary-color) !important;
    color: var(--btn-text-color, #000) !important;
}
.waqar-com-divider {
    border-top: 1px solid var(--border-color) !important;
}

/* ── Modals & Category Containers ── */
.modal-content, #customCommentModal > div, .edit-modal-content {
    background-color: var(--modal-bg, var(--card-bg)) !important;
    border: 1px solid var(--border-color) !important;
    color: var(--text-color) !important;
}
.modal-header, .modal-footer {
    background-color: var(--section-bg, var(--card-bg)) !important;
    border-color: var(--border-color) !important;
}

/* ── Body / Page ── */
body { background-color: var(--bg-color) !important; color: var(--text-color); }
h1,h2,h3,h4,h5,h6 { color: var(--heading-color, var(--text-color)); }
hr { border-color: var(--border-color); }

</style>

{{-- ── 4. Anti-FOUC + SweetAlert theming ─────────────────────── --}}
<script>
/* ── Instant theme detection (no flash) ── */
(function() {
    var saved = localStorage.getItem('ha_theme');
    var theme = saved || ((window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) ? 'light' : 'dark');
    var previewMode = localStorage.getItem('ha_live_theme_mode');
    if (previewMode === 'light' || previewMode === 'dark') {
        theme = previewMode;
    }
    document.documentElement.setAttribute('data-theme', theme);
})();

/* ── Live theme preview bridge (builder -> whole site) ── */
(function() {
    var PALETTE_KEY = 'ha_live_theme_palette';
    var MODE_KEY = 'ha_live_theme_mode';
    var MAP = @json($runtimeThemeMap);

    function isHexColor(value) {
        return typeof value === 'string' && /^#[0-9A-Fa-f]{6}$/i.test(value.trim());
    }

    function clearRuntimePalette() {
        var html = document.documentElement;
        Object.values(MAP).flat().forEach(function(variableName) {
            html.style.removeProperty(variableName);
        });
        html.removeAttribute('data-live-theme');
    }

    function applyRuntimePalette(colors) {
        if (!colors || typeof colors !== 'object') return;

        var html = document.documentElement;

        Object.entries(colors).forEach(function(entry) {
            var key = entry[0];
            var value = entry[1];
            if (!isHexColor(value)) return;

            (MAP[key] || ['--' + key]).forEach(function(variableName) {
                html.style.setProperty(variableName, value.trim());
            });
        });

        html.setAttribute('data-live-theme', '1');
        window.dispatchEvent(new CustomEvent('ha-runtime-theme-applied', { detail: { colors: colors } }));
    }
function syncRuntimeTheme() {
        var rawPalette = sessionStorage.getItem(PALETTE_KEY);
        var previewMode = sessionStorage.getItem(MODE_KEY);

        clearRuntimePalette();

        if (previewMode === 'light' || previewMode === 'dark') {
            document.documentElement.setAttribute('data-theme', previewMode);
        }

        if (!rawPalette) return;

        try {
            applyRuntimePalette(JSON.parse(rawPalette));
        } catch (error) {
            console.warn('Invalid live theme palette payload', error);
        }
    }

  window.HaLiveTheme = {
        sync: syncRuntimeTheme,
        applyPalette: applyRuntimePalette,
        clear: function() {
            sessionStorage.removeItem(PALETTE_KEY);
            sessionStorage.removeItem(MODE_KEY);
            clearRuntimePalette();
        }
    };
})();

/* ── SweetAlert2 theming (runs after Swal is loaded) ── */
(function() {
    var colors = {
        bg:       getComputedStyle(document.documentElement).getPropertyValue('--card-bg').trim()       || '#111111',
        text:     getComputedStyle(document.documentElement).getPropertyValue('--text-color').trim()     || '#FFFFFF',
        border:   getComputedStyle(document.documentElement).getPropertyValue('--border-color').trim()   || '#3A3A3A',
        primary:  getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim()  || '#D4AF37',
        success:  getComputedStyle(document.documentElement).getPropertyValue('--success-color').trim()  || '#28A745',
        danger:   getComputedStyle(document.documentElement).getPropertyValue('--danger-color').trim()   || '#DC3545',
        warning:  getComputedStyle(document.documentElement).getPropertyValue('--warning-color').trim()  || '#FFC107',
        info:     getComputedStyle(document.documentElement).getPropertyValue('--info-color').trim()     || '#17A2B8',
        muted:    getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim()     || '#AAAAAA'
    };

    function applySwalTheme() {
        if (typeof Swal === 'undefined') return;
        var origFire = Swal.fire.bind(Swal);
        Swal.fire = function(opts) {
            /* re-read CSS vars at fire-time so they reflect current theme */
            var cs = getComputedStyle(document.documentElement);
            var themeDefaults = {
                background:   cs.getPropertyValue('--card-bg').trim()     || colors.bg,
                color:        cs.getPropertyValue('--text-color').trim()   || colors.text,
                confirmButtonColor: cs.getPropertyValue('--primary-color').trim()  || colors.primary,
                cancelButtonColor:  cs.getPropertyValue('--text-muted').trim()     || colors.muted,
                customClass: {
                    popup:  'swal-ha-popup',
                    title:  'swal-ha-title',
                    htmlContainer: 'swal-ha-html',
                    confirmButton: 'swal-ha-confirm',
                    cancelButton: 'swal-ha-cancel'
                }
            };

            if (typeof opts === 'string') {
                var stringConfig = {
                    title: opts,
                    text: arguments[1],
                    icon: arguments[2]
                };

                return origFire(Object.assign({}, stringConfig, themeDefaults));
            }

            opts = opts || {};
            var themed = Object.assign({}, opts, themeDefaults, {
                customClass: Object.assign({}, opts.customClass || {}, themeDefaults.customClass)
            });

            return origFire(themed);
        };
    }

    /* Try immediately, then retry after DOM/scripts load */
    applySwalTheme();
    document.addEventListener('DOMContentLoaded', applySwalTheme);
    window.addEventListener('load', applySwalTheme);
})();
</script>

<style>
/* ── SweetAlert2 CSS variable bridge ── */
.swal2-popup.swal-ha-popup {
    background-color: var(--card-bg)    !important;
    color:            var(--text-color) !important;
    border:           1px solid var(--border-color) !important;
    box-shadow:       0 20px 60px var(--shadow-color) !important;
}
.swal2-popup.swal-ha-popup .swal2-title { color: var(--text-color) !important; }
.swal2-popup.swal-ha-popup .swal2-html-container { color: var(--text-muted) !important; }
.swal2-popup.swal-ha-popup .swal2-icon.swal2-success .swal2-success-ring { border-color: var(--success-color) !important; }
.swal2-popup.swal-ha-popup .swal2-icon.swal2-error   [class^=swal2-x-mark-line] { background-color: var(--danger-color)  !important; }
.swal2-popup.swal-ha-popup .swal2-icon.swal2-warning { border-color: var(--warning-color) !important; color: var(--warning-color) !important; }
.swal2-popup.swal-ha-popup .swal2-icon.swal2-info    { border-color: var(--info-color)    !important; color: var(--info-color)    !important; }
.swal2-popup.swal-ha-popup .swal2-confirm,
.swal2-popup.swal-ha-popup .swal2-confirm.swal-ha-confirm {
    background-color: var(--primary-color) !important;
    color:            var(--btn-text-color, #000) !important;
}
.swal2-popup.swal-ha-popup .swal2-cancel,
.swal2-popup.swal-ha-popup .swal2-cancel.swal-ha-cancel {
    background-color: color-mix(in srgb, var(--text-muted) 18%, var(--card-bg)) !important;
    color: var(--text-color) !important;
    border: 1px solid var(--border-color) !important;
}
.swal2-popup.swal-ha-popup .swal2-input,
.swal2-popup.swal-ha-popup .swal2-textarea {
    background-color: var(--input-bg)     !important;
    color:            var(--input-text)   !important;
    border-color:     var(--input-border) !important;
}
/* Toast (top-end small popups) */
.swal2-toast {
    background-color: var(--card-bg)    !important;
    color:            var(--text-color) !important;
}
.swal2-toast .swal2-title { color: var(--text-color) !important; font-size: 0.95rem !important; }

/* ── Runtime cart theming bridge ── */
#cart-count,
#side-cart-count {
    background: var(--primary-color) !important;
    color: var(--btn-text-color, #000) !important;
    border: 1px solid color-mix(in srgb, var(--primary-color) 68%, var(--border-color)) !important;
    box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary-color) 15%, transparent) !important;
}

.header-icon-btn.cart-btn,
.cart-btn {
    color: var(--text-color) !important;
}

.header-icon-btn.cart-btn:hover,
.cart-btn:hover {
    color: var(--primary-color) !important;
}

#side-cart {
    background: color-mix(in srgb, var(--card-bg) 92%, var(--primary-color) 8%) !important;
    color: var(--text-color) !important;
    border-inline-start: 1px solid color-mix(in srgb, var(--primary-color) 28%, var(--border-color)) !important;
    box-shadow: -10px 0 40px color-mix(in srgb, var(--primary-color) 20%, transparent), 0 0 0 1px var(--border-color) !important;
    font-family: var(--font-primary, 'Cairo', sans-serif) !important;
}

#cart-overlay {
    background: rgba(0, 0, 0, 0.3) !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}

#side-cart > div:first-child {
    border-bottom: 1px solid color-mix(in srgb, var(--primary-color) 35%, var(--border-color)) !important;
    background: linear-gradient(90deg, color-mix(in srgb, var(--primary-color) 10%, transparent) 0%, color-mix(in srgb, var(--primary-color) 18%, transparent) 50%, color-mix(in srgb, var(--primary-color) 10%, transparent) 100%) !important;
}

#side-cart > div:first-child h4,
#close-cart,
#side-cart .cart-title,
#notes-near-cart,
.text-gold,
.section-title-gold,
.header-gold {
    color: var(--primary-color) !important;
}

#side-cart .cart-item,
#cart-items-content .cart-item {
    background: color-mix(in srgb, var(--section-bg) 88%, var(--primary-color) 12%) !important;
    border: 1px solid color-mix(in srgb, var(--primary-color) 28%, var(--border-color)) !important;
}

#side-cart .cart-item img {
    border-radius: 6px;
}

#cart-items-content .quantity-wrapper,
#side-cart .quantity-wrapper {
    border: 1px solid color-mix(in srgb, var(--primary-color) 28%, var(--border-color)) !important;
    background: color-mix(in srgb, var(--section-bg) 92%, var(--primary-color) 8%) !important;
}

#cart-items-content .update-cart,
#side-cart .update-cart {
    color: var(--primary-color) !important;
    background: transparent !important;
}

#cart-items-content .qty-num,
#side-cart .qty-num {
    color: var(--text-color) !important;
}

#cart-items-content .remove-from-cart,
#side-cart .remove-from-cart {
    color: var(--danger-color) !important;
}

#side-cart > div:last-child {
    background: color-mix(in srgb, var(--bg-color) 92%, #000 8%) !important;
    border-top: 1px solid color-mix(in srgb, var(--primary-color) 35%, var(--border-color)) !important;
}

#side-cart > div:last-child span,
#side-cart > div:last-child strong,
#side-cart > div:last-child p {
    color: inherit !important;
}

.btn-checkout,
.confirm-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    color: var(--btn-text-color, #000) !important;
    border: 1px solid var(--primary-color) !important;
    box-shadow: 0 10px 24px color-mix(in srgb, var(--primary-color) 24%, transparent) !important;
}

.btn-checkout:hover,
.confirm-btn:hover {
    background: linear-gradient(135deg, var(--hover-color, var(--primary-color)), var(--secondary-color)) !important;
}

.login-notice,
.ship-opt.active,
.price-section,
.breadcrumb,
.discount-badge,
.cart-box:not(.disabled):hover {
    box-shadow: 0 0 0 1px color-mix(in srgb, var(--primary-color) 22%, transparent), 0 14px 32px color-mix(in srgb, var(--primary-color) 18%, transparent) !important;
}

/* ── Google Translate Seamless Integration Overrides ─────── */
#google_translate_element,
.skiptranslate:not(.lang-switcher) {
    display: none !important;
}
iframe.goog-te-banner-frame,
.goog-te-banner-frame,
.goog-te-banner,
.goog-te-spinner-pos,
#goog-gt-tt,
.goog-te-balloon-frame,
.goog-tooltip,
.goog-tooltip:hover {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
    height: 0 !important;
    width: 0 !important;
}
body {
    top: 0px !important;
    position: static !important;
}
html {
    top: 0px !important;
}
.goog-text-highlight {
    background-color: transparent !important;
    box-shadow: none !important;
}
font {
    background-color: transparent !important;
    box-shadow: none !important;
}
</style>

{{-- ── 5. Google Fonts ─────────────────────────────────────── --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

{{-- ── 6. Google Translate Hidden Element & Auto Init ──────── --}}
<div id="google_translate_element" style="display:none;"></div>
<script type="text/javascript">
    function googleTranslateElementInit() {
        if (typeof google !== 'undefined' && google.translate) {
            new google.translate.TranslateElement({
                pageLanguage: 'ar',
                includedLanguages: 'ar,en,fr',
                autoDisplay: false
            }, 'google_translate_element');
        }
    }

    (function() {
        function getSavedLang() {
            var nameEQ = "googtrans=";
            var ca = document.cookie.split(';');
            for(var i=0; i < ca.length; i++) {
                var c = ca[i].trim();
                if (c.indexOf(nameEQ) === 0) {
                    var val = c.substring(nameEQ.length);
                    var parts = val.split('/');
                    var l = parts[parts.length - 1];
                    if (l && ['ar', 'en', 'fr'].indexOf(l) !== -1) return l;
                }
            }
            var serverLocale = "{{ app()->getLocale() }}";
            if (serverLocale && ['ar', 'en', 'fr'].indexOf(serverLocale) !== -1) return serverLocale;
            return 'ar';
        }

        window.triggerGoogleTranslate = function(langCode) {
            var count = 0;
            var maxTries = 40;
            var interval = setInterval(function() {
                var combo = document.querySelector('.goog-te-combo');
                if (combo) {
                    if (combo.value !== langCode) {
                        combo.value = langCode;
                        combo.dispatchEvent(new Event('change'));
                    }
                    clearInterval(interval);
                }
                count++;
                if (count >= maxTries) clearInterval(interval);
            }, 100);
        };

        window.lockArabicNavbarLabels = function() {
            var activeLang = getSavedLang();
            if (activeLang === 'ar') {
                var home = document.querySelector('.nav-text-home');
                var cat  = document.querySelector('.nav-text-cat');
                if (home && home.textContent.trim() !== 'الرئيسية') {
                    home.textContent = 'الرئيسية';
                }
                if (cat && cat.textContent.trim() !== 'الأقسام') {
                    cat.textContent = 'الأقسام';
                }
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            var activeLang = getSavedLang();
            if (activeLang && activeLang !== 'ar') {
                window.triggerGoogleTranslate(activeLang);
                document.documentElement.setAttribute('dir', 'ltr');
                document.documentElement.setAttribute('lang', activeLang);
            } else {
                window.lockArabicNavbarLabels();
            }

            // Periodically keep navbar labels locked correctly in Arabic mode
            setInterval(window.lockArabicNavbarLabels, 500);
        });
    })();
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>

