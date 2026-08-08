<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('settings.title') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">

    <style>
        /* =========================================================
           TOKENS — palette, type scale, spacing, radii
           A boutique admin: warm charcoal ground, restrained gold,
           one signature motif (the "clasp" on each card).
        ========================================================= */
        :root{
            --bg:            var(--bg-color, #0b0b0d);
            --bg-soft:       var(--section-bg, #121214);
            --surface:       var(--card-bg, #17171a);
            --surface-raised:var(--card-bg, #1c1c1f);
            --border:        var(--border-color, rgba(201,162,39,.16));
            --border-strong: var(--primary-color, rgba(201,162,39,.34));

            --gold:          var(--primary-color, #c9a227);
            --gold-light:    var(--accent-color, #e8cc6b);
            --gold-dim:      var(--secondary-color, #8a7328);

            --ink:           var(--text-color, #f3efe4);
            --ink-muted:     var(--text-muted, #a8a297);
            --ink-faint:     var(--text-muted, #6f6a60);

            --success:       var(--success-color, #3fb373);
            --success-bg:    color-mix(in srgb, var(--success-color) 12%, transparent);
            --danger:        var(--danger-color, #e5555c);
            --danger-bg:     color-mix(in srgb, var(--danger-color, #e5555c) 12%, transparent);
            --whatsapp:      #2fbf60;

            --radius-lg: 18px;
            --radius-md: 12px;
            --radius-sm: 8px;

            --shadow-card: 0 20px 40px -12px rgba(0,0,0,.55);
            --ease: cubic-bezier(.4,0,.2,1);
        }

        *{ box-sizing: border-box; }

        body{
            font-family: 'Cairo', sans-serif;
            background:
                radial-gradient(1200px 600px at 15% -10%, rgba(201,162,39,.06), transparent 60%),
                var(--bg);
            color: var(--ink);
            margin: 0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        ::selection{ background: var(--gold); color: var(--bg); }

        /* focus-visible everywhere — accessibility floor */
        a:focus-visible, button:focus-visible, input:focus-visible,
        select:focus-visible, label:focus-visible{
            outline: 2px solid var(--gold-light);
            outline-offset: 2px;
            border-radius: 6px;
        }

        @media (prefers-reduced-motion: reduce){
            *{ animation: none !important; transition: none !important; }
        }

        /* =========================================================
           LAYOUT
        ========================================================= */
        .main-content-wrapper{
            padding: 34px 28px 56px;
            margin-right: 260px; /* width of sidebar */
            transition: margin .3s var(--ease);
        }

        .page-head{
            max-width: 1120px;
            margin: 0 auto 28px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border);
            padding-bottom: 22px;
        }
        .page-head .eyebrow{
            display:flex; align-items:center; gap:8px;
            color: var(--gold);
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: .3px;
            margin-bottom: 6px;
        }
        .page-head .eyebrow i{ font-size: 11px; }
        .page-head h1{
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: var(--ink);
        }
        .page-head p{
            margin: 6px 0 0;
            color: var(--ink-muted);
            font-size: 14px;
        }
        .page-head .back-link{
            display:inline-flex; align-items:center; gap:8px;
            padding: 10px 18px;
            border: 1px solid var(--border-strong);
            border-radius: 999px;
            color: var(--gold-light);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background .2s var(--ease), color .2s var(--ease);
            white-space: nowrap;
        }
        .page-head .back-link:hover{
            background: var(--gold);
            color: var(--bg);
        }
        .page-head .back-link i{ font-size: 13px; }

        /* success banner */
        .alert-success{
            max-width: 1120px;
            margin: 0 auto 22px;
            display:flex; align-items:center; gap:10px;
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(63,179,115,.35);
            padding: 14px 18px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
        }

        /* card grid */
        .cards-container{
            max-width: 1120px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
            align-items: start;
        }

        /* =========================================================
           CARD — signature element: a small gold "clasp" diamond
           centered on the top edge, echoing a garment fastening.
        ========================================================= */
        .settings-card{
            position: relative;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            display: flex;
            flex-direction: column;
            overflow: visible;
        }
        .settings-card::before{
            content: "";
            position: absolute;
            top: -7px;
            right: 50%;
            transform: translateX(50%) rotate(45deg);
            width: 13px; height: 13px;
            background: var(--gold);
            border-radius: 3px;
            box-shadow: 0 2px 10px rgba(201,162,39,.5);
        }

        .card-header{
            display:flex; align-items:center; gap:12px;
            padding: 22px 24px 18px;
            border-bottom: 1px solid var(--border);
        }
        .card-header .icon-badge{
            width: 40px; height: 40px;
            border-radius: var(--radius-sm);
            background: linear-gradient(150deg, var(--gold) 0%, var(--gold-dim) 100%);
            display:flex; align-items:center; justify-content:center;
            color: var(--bg);
            font-size: 16px;
            flex-shrink: 0;
        }
        .card-header .titles h2{
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
        }
        .card-header .titles p{
            margin: 2px 0 0;
            font-size: 12.5px;
            color: var(--ink-faint);
        }

        .card-body{ padding: 22px 24px 24px; }

        /* =========================================================
           FORM ELEMENTS
        ========================================================= */
        .field{ margin-bottom: 20px; }
        .field:last-child{ margin-bottom: 0; }

        .field label{
            display:block;
            color: var(--ink);
            font-weight: 600;
            font-size: 13.5px;
            margin-bottom: 8px;
        }
        .field .hint{
            display:block;
            color: var(--ink-faint);
            font-size: 11.5px;
            margin-top: 6px;
            line-height: 1.5;
        }

        input[type="text"], input[type="number"], select{
            width: 100%;
            padding: 11px 14px;
            background: var(--bg-soft);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius-sm);
            color: var(--ink);
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: border-color .2s var(--ease), box-shadow .2s var(--ease);
        }
        input[type="text"]::placeholder, input[type="number"]::placeholder{ color: var(--ink-faint); }
        input:hover, select:hover{ border-color: rgba(201,162,39,.3); }
        input:focus, select:focus{
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,162,39,.14);
        }

        select{
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23c9a227'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 14px center;
            padding-left: 34px;
        }

        /* prefixed text input (whatsapp number) */
        .input-prefix{ position: relative; }
        .input-prefix i{
            position: absolute;
            top: 50%; right: 14px;
            transform: translateY(-50%);
            color: var(--whatsapp);
            font-size: 15px;
            pointer-events: none;
        }
        .input-prefix input{ padding-right: 38px; }

        /* --- toggle-card radio group (shipping type / layouts) --- */
        .choice-group{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .choice-group input{ position: absolute; opacity: 0; pointer-events: none; }
        .choice-card{
            display:flex; flex-direction: column; gap: 4px;
            padding: 13px 14px;
            background: var(--bg-soft);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: border-color .2s var(--ease), background .2s var(--ease);
        }
        .choice-card i{ color: var(--gold); font-size: 15px; }
        .choice-card .name{ font-size: 13.5px; font-weight: 700; color: var(--ink); }
        .choice-card .desc{ font-size: 11px; color: var(--ink-faint); }
        .choice-group input:checked + .choice-card{
            border-color: var(--gold);
            background: rgba(201,162,39,.08);
        }
        .choice-group input:focus-visible + .choice-card{
            outline: 2px solid var(--gold-light);
            outline-offset: 2px;
        }

        /* reveal the fixed-fee field only when "fixed" is chosen — no JS needed */
        .fee-field{ display: none; margin-top: 14px; }
        .shipping-fields:has(#shipping_fixed:checked) .fee-field{ display: block; }

        /* color swatch row */
        .color-row{ display:flex; align-items:center; gap: 12px; }
        input[type="color"]{
            -webkit-appearance: none;
            width: 44px; height: 44px;
            padding: 0;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius-sm);
            background: var(--bg-soft);
            cursor: pointer;
        }
        input[type="color"]::-webkit-color-swatch-wrapper{ padding: 4px; }
        input[type="color"]::-webkit-color-swatch{ border: none; border-radius: 5px; }
        .color-row .hint{ margin-top: 0; }

        /* category row */
        .category-row{
            display:flex; align-items:center; gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }
        .category-row:last-of-type{ border-bottom: none; }
        .category-row .cat-name{
            flex: 1;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ink);
            display:flex; align-items:center; gap:8px;
        }
        .category-row .cat-name::before{
            content:"";
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--gold-dim);
            flex-shrink:0;
        }
        .category-row select{ flex: 1.1; min-width: 150px; }

        /* buttons */
        .btn-save{
            width: 100%;
            padding: 13px;
            margin-top: 22px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dim) 100%);
            border: none;
            border-radius: 999px;
            color: var(--bg);
            font-weight: 700;
            font-size: 14px;
            font-family: inherit;
            cursor: pointer;
            display:flex; align-items:center; justify-content:center; gap:8px;
            transition: transform .15s var(--ease), box-shadow .15s var(--ease);
        }
        .btn-save:hover{ box-shadow: 0 10px 24px -8px rgba(201,162,39,.55); transform: translateY(-1px); }
        .btn-save:active{ transform: translateY(0); }

        .btn-save.is-secondary{
            background: transparent;
            border: 1px solid rgba(47,191,96,.5);
            color: var(--whatsapp);
        }
        .btn-save.is-secondary:hover{
            background: rgba(47,191,96,.1);
            box-shadow: none;
        }

        .divider{ border: 0; border-top: 1px solid var(--border); margin: 22px 0 18px; }

        .list-label{
            display:flex; align-items:center; justify-content: space-between;
            font-size: 12.5px; font-weight: 700; color: var(--ink-muted);
            margin-bottom: 10px;
        }
        .list-label .count{
            font-size: 11px; color: var(--ink-faint); font-weight: 600;
        }

        .numbers-list{ display:flex; flex-direction: column; gap: 8px; }
        .number-item{
            display:flex; align-items:center; justify-content: space-between;
            background: var(--bg-soft);
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255,255,255,.06);
        }
        .number-item .num{
            display:flex; align-items:center; gap:9px;
            font-size: 13.5px; color: var(--ink);
            direction: ltr;
        }
        .number-item .num i{ color: var(--whatsapp); font-size: 14px; }
        .btn-delete{
            background: none; border: none;
            color: var(--ink-faint);
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: color .2s var(--ease), background .2s var(--ease);
        }
        .btn-delete:hover{ color: var(--danger); background: var(--danger-bg); }

        .empty-state{
            text-align: center;
            padding: 22px 10px;
            color: var(--ink-faint);
            font-size: 12.5px;
        }
        .empty-state i{ display:block; font-size: 20px; margin-bottom: 8px; color: var(--ink-faint); }

        .back-to-store-container{
            max-width: 1120px;
            margin: 34px auto 0;
            text-align: center;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */
        @media (max-width: 992px){
            .main-content-wrapper{ margin-right: 0; padding: 24px 16px 44px; }
            .cards-container{ grid-template-columns: 1fr; }
        }
        @media (max-width: 480px){
            .choice-group{ grid-template-columns: 1fr; }
            .page-head{ flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
@include('admin.header')

@include('admin.sidebar')

<div class="main-content-wrapper">

    <div class="page-head">
        <div>
            <div class="eyebrow"><i class="fas fa-gem"></i> {{ __('settings.admin_panel') }}</div>
            <h1>{{ __('settings.title') }}</h1>
            <p>{{ __('settings.shipping_settings') }}</p>
        </div>
        <a href="{{ url('/') }}" class="back-link">
            {{ __('settings.back_to_store') }} <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="cards-container">


        <!-- إعدادات الشحن -->
        <div class="settings-card">
            <div class="card-header">
                <div class="icon-badge"><i class="fas fa-truck"></i></div>
                <div class="titles">
                    <h2>{{ __('settings.shipping_settings') }}</h2>
                    <p>{{ __('settings.shipping_type') }}</p>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="field shipping-fields">
                        <label>{{ __('settings.shipping_type') }}</label>
                        <div class="choice-group">
                            <input type="radio" id="shipping_free" name="shipping_type" value="free"
                                {{ ($settings['shipping_type'] ?? '') == 'free' ? 'checked' : '' }}>
                            <label for="shipping_free" class="choice-card">
                                <i class="fas fa-gift"></i>
                                <span class="name">{{ __('settings.shipping_free') }}</span>
                            </label>

                            <input type="radio" id="shipping_fixed" name="shipping_type" value="fixed"
                                {{ ($settings['shipping_type'] ?? '') == 'fixed' ? 'checked' : '' }}>
                            <label for="shipping_fixed" class="choice-card">
                                <i class="fas fa-tag"></i>
                                <span class="name">{{ __('settings.shipping_fixed') }}</span>
                            </label>
                        </div>

                        <input type="radio" id="shipping_region" name="shipping_type" value="region"
                            {{ ($settings['shipping_type'] ?? '') == 'region' ? 'checked' : '' }}>
                        <label for="shipping_region" class="choice-card">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="name">{{ __('settings.shipping_region') }}</span>
                            <span class="desc">{{ __('settings.shipping_region_desc') }}</span>
                        </label>

                        <div class="fee-field">
                            <label>{{ __('settings.shipping_fee') }}</label>
                            <input type="number" name="shipping_fee" value="{{ $settings['shipping_fee'] ?? 0 }}" min="0" step="0.01">
                            <span class="hint">{{ __('settings.shipping_fee_hint') }}</span>
                        </div>
                    </div>

                    <div class="region-fields">
                        <label>{{ __('settings.shipping_regions') }}</label>
                        <div id="regions-wrapper">
                            @php
                                $regions = isset($settings['shipping_regions'])
                                    ? json_decode($settings['shipping_regions'], true)
                                    : [];
                            @endphp

                            @forelse($regions as $i => $region)
                                <div class="region-row">
                                    <input type="text" name="shipping_regions[{{ $i }}][name]"
                                           placeholder="{{ __('settings.region_name') }}" value="{{ $region['name'] ?? '' }}">
                                    <input type="number" name="shipping_regions[{{ $i }}][fee]"
                                           placeholder="{{ __('settings.region_price') }}" min="0" step="0.01" value="{{ $region['fee'] ?? '' }}">
                                    <button type="button" class="btn-remove-region"><i class="fas fa-trash"></i></button>
                                </div>
                            @empty
                                <div class="region-row">
                                    <input type="text" name="shipping_regions[0][name]" placeholder="{{ __('settings.region_name') }}">
                                    <input type="number" name="shipping_regions[0][fee]" placeholder="{{ __('settings.region_price') }}" min="0" step="0.01">
                                    <button type="button" class="btn-remove-region"><i class="fas fa-trash"></i></button>
                                </div>
                            @endforelse
                        </div>

                        <button type="button" id="add-region-btn" class="btn-add-region" style="color: var(--primary-color)">
                            <i class="fas fa-plus" style="color: var(--primary-color)"></i> {{ __('settings.add_region') }}
                        </button>
                    </div>

                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> {{ __('settings.update_shipping') }}</button>
                </form>
            </div>
        </div>

        <!-- أرقام الواتساب -->
        <div class="settings-card">
            <div class="card-header">
                <div class="icon-badge"><i class="fab fa-whatsapp"></i></div>
                <div class="titles">
                    <h2>{{ __('settings.whatsapp_numbers_title') }}</h2>
                    <p>{{ __('settings.whatsapp_numbers_desc') }}</p>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label>{{ __('settings.new_number') }}</label>
                        <div class="input-prefix">
                            <input type="text" name="new_whatsapp_number" placeholder="{{ __('settings.number_placeholder') }}" required>
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <span class="hint">{{ __('settings.number_hint') }}</span>
                    </div>

                    <button type="submit" class="btn-save is-secondary">
                        <i class="fas fa-plus-circle"></i> {{ __('settings.add_number') }}
                    </button>
                </form>

                <hr class="divider">

                @php
                    $numbers = isset($settings['whatsapp_numbers']) ? json_decode($settings['whatsapp_numbers'], true) : [];
                @endphp

                <div class="list-label">
                    <span>{{ __('settings.current_numbers') }}</span>
                    <span class="count">{{ is_array($numbers) ? count($numbers) : 0 }}</span>
                </div>

                <div class="numbers-list">
                    @forelse($numbers as $index => $num)
                        <div class="number-item">
                            <span class="num"><i class="fab fa-whatsapp"></i> {{ $num }}</span>
                            <form action="{{ route('admin.settings.delete_number') }}" method="POST" style="margin:0">
                                @csrf @method('DELETE')
                                <input type="hidden" name="number_index" value="{{ $index }}">
                                <button type="submit" class="btn-delete" aria-label="{{ __('settings.delete_number') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            {{ __('settings.no_numbers') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- أشكال عرض الأقسام -->
        <div class="settings-card">
            <div class="card-header">
                <div class="icon-badge"><i class="fas fa-th-large"></i></div>
                <div class="titles">
                    <h2>{{ __('settings.category_display_title') }}</h2>
                    <p>{{ __('settings.category_display_desc') }}</p>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @foreach($categories as $cat)
                        <div class="category-row">
                            <span class="cat-name">{{ $cat->name }}</span>
                            <select name="category_style[{{ $cat->id }}]">
                                @php
                                    $currentStyle = $settings['category_style_' . $cat->id] ?? 'general-frame';
                                @endphp
                                <option value="general-frame" {{ $currentStyle == 'general-frame' ? 'selected' : '' }}>{{ __('settings.style_square') }}</option>
                                <option value="abaya-frame" {{ $currentStyle == 'abaya-frame' ? 'selected' : '' }}>{{ __('settings.style_tall') }}</option>
                                <option value="circle-frame" {{ $currentStyle == 'circle-frame' ? 'selected' : '' }}>{{ __('settings.style_circle') }}</option>
                            </select>
                        </div>
                    @endforeach

                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> {{ __('settings.update_categories') }}</button>
                </form>
            </div>
        </div>

        <!-- تخصيص منتجات قد تعجبك -->
        <div class="settings-card">
            <div class="card-header">
                <div class="icon-badge"><i class="fas fa-magic"></i></div>
                <div class="titles">
                    <h2>{{ __('settings.related_products_title') }}</h2>
                    <p>{{ __('settings.related_products_desc') }}</p>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf

                    <div class="field">
                        <label>{{ __('settings.display_style') }}</label>
                        <select name="related_products_layout">
                            @php $layout = $settings['related_products_layout'] ?? 'curved-slider'; @endphp
                            <option value="curved-slider" {{ $layout == 'curved-slider' ? 'selected' : '' }}>{{ __('settings.layout_curved_slider') }}</option>
                            <option value="grid-view" {{ $layout == 'grid-view' ? 'selected' : '' }}>{{ __('settings.layout_grid') }}</option>
                            <option value="horizontal-scroll" {{ $layout == 'horizontal-scroll' ? 'selected' : '' }}>{{ __('settings.layout_horizontal') }}</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>{{ __('settings.title_animation') }}</label>
                        <select name="title_animation_style">
                            @php $anim = $settings['title_animation_style'] ?? 'blur-effect'; @endphp
                            <option value="blur-effect" {{ $anim == 'blur-effect' ? 'selected' : '' }}>{{ __('settings.anim_blur') }}</option>
                            <option value="typing-effect" {{ $anim == 'typing-effect' ? 'selected' : '' }}>{{ __('settings.anim_typing') }}</option>
                            <option value="fade-slide" {{ $anim == 'fade-slide' ? 'selected' : '' }}>{{ __('settings.anim_slide') }}</option>
                        </select>
                        <span class="hint">{{ __('settings.anim_hint') }}</span>
                    </div>

                    <div class="field">
                        <label>{{ __('settings.title_glow_color') }}</label>
                        <div class="color-row">
                            <input type="color" name="title_anim_color" value="{{ $settings['title_anim_color'] ?? '#000000' }}">
                            <span class="hint">{{ __('settings.title_glow_color') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> {{ __('settings.save_changes_btn') }}</button>
                </form>
            </div>
        </div>

    </div>

    <div class="back-to-store-container">
        <a href="{{ url('/') }}" class="btn-back" style="display:none">{{-- kept for backward compat, hidden --}}</a>
    </div>

</div>

@include('admin.footer')

















<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('regions-wrapper');
    const addBtn = document.getElementById('add-region-btn');

    function nextIndex() {
        return wrapper.querySelectorAll('.region-row').length
            ? Math.max(...[...wrapper.querySelectorAll('input[name*="[name]"]')]
                .map(el => parseInt(el.name.match(/\[(\d+)\]/)[1]))) + 1
            : 0;
    }

    addBtn.addEventListener('click', function () {
        const idx = nextIndex();
        const row = document.createElement('div');
        row.className = 'region-row';
        row.innerHTML = `
            <input type="text" name="shipping_regions[${idx}][name]" placeholder="اسم المنطقة">
            <input type="number" name="shipping_regions[${idx}][fee]" placeholder="السعر" min="0" step="0.01">
            <button type="button" class="btn-remove-region"><i class="fas fa-trash"></i></button>
        `;
        wrapper.appendChild(row);
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-region')) {
            const rows = wrapper.querySelectorAll('.region-row');
            if (rows.length > 1) {
                e.target.closest('.region-row').remove();
            }
        }
    });
});
</script>

</body>
</html>