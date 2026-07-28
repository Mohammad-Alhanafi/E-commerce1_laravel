{{--
    ============================================================
    components/language-switcher.blade.php
    ────────────────────────────────────────────────────────────
    PURPOSE:
      A beautiful dropdown language switcher for the navbar.
      Shows flag emoji + current language name.
      Switches locale via GET /lang/{locale} route.
      Stores locale in session (persists across page refreshes).

    USAGE:
      Add inside .header-icons div in admin/header.blade.php:
      @include('components.language-switcher')

    Supported locales: ar, en, fr
    ============================================================
--}}

@php
    // Get current locale — fallback to app default
    $currentLocale = app()->getLocale() ?? config('app.locale', 'ar');

    // Available languages
    $languages = [
        'ar' => ['flag' => '🇸🇦', 'label' => 'العربية',  'short' => 'AR'],
        'en' => ['flag' => '🇺🇸', 'label' => 'English',   'short' => 'EN'],
        'fr' => ['flag' => '🇫🇷', 'label' => 'Français',  'short' => 'FR'],
    ];

    $current = $languages[$currentLocale] ?? $languages['ar'];
@endphp

{{-- Language Switcher Wrapper --}}
<div class="lang-switcher" id="langSwitcher" aria-label="{{ __('navbar.language_switcher', [], $currentLocale) ?? 'Language' }}">

    {{-- Current Language Button --}}
    <button
        type="button"
        class="lang-switcher-btn"
        id="langSwitcherBtn"
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-controls="langDropdownMenu"
    >
        <span class="lang-flag" aria-hidden="true">{{ $current['flag'] }}</span>
        <span class="lang-label">{{ $current['short'] }}</span>
        <span class="lang-chevron" aria-hidden="true">▾</span>
    </button>

    {{-- Dropdown Menu --}}
    <div
        class="lang-dropdown"
        id="langDropdownMenu"
        role="listbox"
        aria-label="Select Language"
    >
        @foreach($languages as $code => $lang)
            <a
                href="{{ route('lang.switch', $code) }}"
                class="lang-option {{ $currentLocale === $code ? 'active-lang' : '' }}"
                role="option"
                aria-selected="{{ $currentLocale === $code ? 'true' : 'false' }}"
                title="{{ $lang['label'] }}"
            >
                <span class="lang-opt-flag" aria-hidden="true">{{ $lang['flag'] }}</span>
                <span class="lang-opt-name">{{ $lang['label'] }}</span>
                <span class="lang-opt-check" aria-hidden="true">✓</span>
            </a>
        @endforeach
    </div>
</div>

{{-- Language Switcher JavaScript ─────────────────────────────
     Pure JS — no jQuery. Toggles the open class on click.
     Closes on outside click.
--}}
<script>
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var switcher = document.getElementById('langSwitcher');
        var btn      = document.getElementById('langSwitcherBtn');

        if (!switcher || !btn) return;

        /**
         * Toggle the dropdown open/closed
         */
        function toggle() {
            var isOpen = switcher.classList.toggle('open');
            btn.setAttribute('aria-expanded', String(isOpen));
        }

        /**
         * Close the dropdown
         */
        function close() {
            switcher.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        // Open/close on button click
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggle();
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!switcher.contains(e.target)) {
                close();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') close();
        });
    });
})();
</script>
