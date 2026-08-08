{{--
    ============================================================
    components/language-switcher.blade.php
    ────────────────────────────────────────────────────────────
    PURPOSE:
      A beautiful dropdown language switcher for the navbar.
      Shows flag emoji + current language name.
      Translates page instantly via Google Translate without page refresh,
      sets googtrans cookie for automatic translation across page navigation,
      and syncs Laravel session & cookie via background AJAX.

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
<div class="lang-switcher" id="langSwitcher" aria-label="{{ __('navbar.language_switcher') }}">

    {{-- Current Language Button --}}
    <button
        type="button"
        class="lang-switcher-btn"
        id="langSwitcherBtn"
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-controls="langDropdownMenu"
    >
        <span class="lang-flag" id="langCurrentFlag" aria-hidden="true">{{ $current['flag'] }}</span>
        <span class="lang-label" id="langCurrentShort">{{ $current['short'] }}</span>
        <span class="lang-chevron" aria-hidden="true">▾</span>
    </button>

    {{-- Dropdown Menu --}}
    <div
        class="lang-dropdown"
        id="langDropdownMenu"
        role="listbox"
        aria-label="{{ __('navbar.language_switcher') }}"
    >
        @foreach($languages as $code => $lang)
            <a
                href="{{ route('lang.switch', $code) }}"
                data-lang="{{ $code }}"
                data-flag="{{ $lang['flag'] }}"
                data-short="{{ $lang['short'] }}"
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
     Instant Google Translate + AJAX session sync without refresh
--}}
<script>
(function () {
    'use strict';

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function setGoogtransCookie(targetLang) {
        var val = (targetLang === 'ar') ? '/ar/ar' : ('/ar/' + targetLang);
        var expires = "; expires=" + new Date(Date.now() + 365*24*60*60*1000).toUTCString();
        
        document.cookie = "googtrans=" + val + expires + "; path=/;";
        
        var host = window.location.hostname;
        if (host && host !== 'localhost' && !/^\d+\.\d+\.\d+\.\d+$/.test(host)) {
            document.cookie = "googtrans=" + val + expires + "; domain=" + host + "; path=/;";
            document.cookie = "googtrans=" + val + expires + "; domain=." + host.replace(/^www\./, '') + "; path=/;";
        }
    }

    function triggerGoogleTranslateSelect(targetLang) {
        var selectElem = document.querySelector('.goog-te-combo');
        if (selectElem) {
            selectElem.value = targetLang;
            selectElem.dispatchEvent(new Event('change'));
            return true;
        }
        return false;
    }

    window.switchAppLanguage = function (targetLang, flag, shortCode) {
        // 1. Set Google Translate Cookie
        setGoogtransCookie(targetLang);

        // 2. Trigger Google Translate Widget (Live instant translation)
        if (typeof window.triggerGoogleTranslate === 'function') {
            window.triggerGoogleTranslate(targetLang);
        } else {
            var triggered = triggerGoogleTranslateSelect(targetLang);
            if (!triggered) {
                setTimeout(function() {
                    triggerGoogleTranslateSelect(targetLang);
                }, 400);
            }
        }

        // 3. Update Document Direction & Lang attribute
        var isRtl = (targetLang === 'ar');
        document.documentElement.setAttribute('dir', isRtl ? 'rtl' : 'ltr');
        document.documentElement.setAttribute('lang', targetLang);
        if (targetLang === 'ar' && typeof window.lockArabicNavbarLabels === 'function') {
            window.lockArabicNavbarLabels();
        }

        // 4. Update UI Switcher Button
        var flagSpan  = document.getElementById('langCurrentFlag');
        var shortSpan = document.getElementById('langCurrentShort');
        if (flagSpan && flag) flagSpan.textContent = flag;
        if (shortSpan && shortCode) shortSpan.textContent = shortCode;

        var options = document.querySelectorAll('.lang-option');
        options.forEach(function (opt) {
            var optLang = opt.getAttribute('data-lang');
            if (optLang === targetLang) {
                opt.classList.add('active-lang');
                opt.setAttribute('aria-selected', 'true');
            } else {
                opt.classList.remove('active-lang');
                opt.setAttribute('aria-selected', 'false');
            }
        });

        // 5. Sync Laravel session via AJAX in background
        fetch('/lang/' + targetLang, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).catch(function (err) {
            console.log('Backend locale sync notice:', err);
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        var switcher = document.getElementById('langSwitcher');
        var btn      = document.getElementById('langSwitcherBtn');

        if (!switcher || !btn) return;

        function toggle() {
            var isOpen = switcher.classList.toggle('open');
            btn.setAttribute('aria-expanded', String(isOpen));
        }

        function close() {
            switcher.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggle();
        });

        document.addEventListener('click', function (e) {
            if (!switcher.contains(e.target)) {
                close();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') close();
        });

        var options = document.querySelectorAll('.lang-option');
        options.forEach(function (opt) {
            opt.addEventListener('click', function (e) {
                e.preventDefault();
                var targetLang = opt.getAttribute('data-lang');
                var flag       = opt.getAttribute('data-flag');
                var shortCode  = opt.getAttribute('data-short');

                close();
                window.switchAppLanguage(targetLang, flag, shortCode);
            });
        });

        // Sync UI on page load if googtrans cookie exists
        var googtrans = getCookie('googtrans');
        if (googtrans) {
            var parts = googtrans.split('/');
            var activeLang = parts[parts.length - 1];
            if (activeLang && ['ar', 'en', 'fr'].indexOf(activeLang) !== -1) {
                var activeOpt = document.querySelector('.lang-option[data-lang="' + activeLang + '"]');
                if (activeOpt) {
                    var flag = activeOpt.getAttribute('data-flag');
                    var shortCode = activeOpt.getAttribute('data-short');
                    var flagSpan  = document.getElementById('langCurrentFlag');
                    var shortSpan = document.getElementById('langCurrentShort');
                    if (flagSpan && flag) flagSpan.textContent = flag;
                    if (shortSpan && shortCode) shortSpan.textContent = shortCode;

                    options.forEach(function (opt) {
                        if (opt.getAttribute('data-lang') === activeLang) {
                            opt.classList.add('active-lang');
                            opt.setAttribute('aria-selected', 'true');
                        } else {
                            opt.classList.remove('active-lang');
                            opt.setAttribute('aria-selected', 'false');
                        }
                    });

                    var isRtl = (activeLang === 'ar');
                    document.documentElement.setAttribute('dir', isRtl ? 'rtl' : 'ltr');
                    document.documentElement.setAttribute('lang', activeLang);
                }
            }
        }
    });
})();
</script>
