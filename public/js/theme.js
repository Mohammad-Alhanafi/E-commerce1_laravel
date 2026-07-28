/**
 * =============================================================
 *  theme.js — Honey Abayah E-Commerce
 *  Dark / Light Theme Toggle System
 *  ─────────────────────────────────────────────────────────
 *  Features:
 *    • Reads saved preference from localStorage
 *    • Falls back to OS prefers-color-scheme if no saved pref
 *    • Applies data-theme attribute to <html> element
 *    • Creates floating toggle button with Moon/Sun icons
 *    • Ripple click animation
 *    • Zero dependencies — pure ES6 JavaScript
 *    • Prevents FOUC via inline <script> in <head> (theme-head.blade.php)
 * =============================================================
 */

(function () {
    'use strict';

    /* ----------------------------------------------------------
       CONFIG
    ---------------------------------------------------------- */
    const STORAGE_KEY   = 'ha_theme';      // localStorage key
    const DARK_THEME    = 'dark';
    const LIGHT_THEME   = 'light';
    const DEFAULT_THEME = DARK_THEME;      // Dark is the default

    /* ----------------------------------------------------------
       ICON CONSTANTS
    ---------------------------------------------------------- */
    const ICONS = {
        dark:  '🌙',   // shown when dark is active (click → switch to light)
        light: '☀️',   // shown when light is active (click → switch to dark)
    };

    const TITLES = {
        dark:  'التبديل إلى الوضع المضيء',
        light: 'التبديل إلى الوضع الداكن',
    };

    /* ----------------------------------------------------------
       CORE — GET / SAVE / APPLY
    ---------------------------------------------------------- */

    /**
     * Detect the user's preferred theme.
     * Priority: localStorage → OS preference → default
     */
    function getPreferredTheme() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved === DARK_THEME || saved === LIGHT_THEME) {
            return saved;
        }
        // Check OS preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
            return LIGHT_THEME;
        }
        return DEFAULT_THEME;
    }

    /**
     * Save theme preference to localStorage.
     * @param {string} theme
     */
    function saveTheme(theme) {
        localStorage.setItem(STORAGE_KEY, theme);
    }

    /**
     * Apply theme to the document.
     * Sets data-theme on <html> and updates the toggle button.
     * @param {string} theme
     */
    function applyTheme(theme) {
        const html = document.documentElement;

        // Set data-theme attribute — CSS picks this up
        html.setAttribute('data-theme', theme);

        // Update toggle button if it exists
        const btn = document.getElementById('theme-toggle-btn');
        if (btn) {
            updateButtonAppearance(btn, theme);
        }

        // Dispatch custom event so other scripts can react
        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
    }

    /**
     * Toggle between dark and light.
     */
    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || DEFAULT_THEME;
        const next    = current === DARK_THEME ? LIGHT_THEME : DARK_THEME;
        applyTheme(next);
        saveTheme(next);
    }

    /* ----------------------------------------------------------
       BUTTON — Create & Update
    ---------------------------------------------------------- */

    /**
     * Update the toggle button icon and aria-label.
     * @param {HTMLElement} btn
     * @param {string}      theme   — currently applied theme
     */
    function updateButtonAppearance(btn, theme) {
        const iconEl = btn.querySelector('.btn-icon');
        if (iconEl) {
            iconEl.textContent = ICONS[theme];
            iconEl.style.transform = 'scale(0)';
            // Re-trigger animation via forced reflow
            void iconEl.offsetWidth;
            iconEl.style.transform = 'scale(1)';
        }
        btn.setAttribute('aria-label', TITLES[theme]);
        btn.setAttribute('title',      TITLES[theme]);
    }

    /**
     * Inject the ripple element on click.
     * @param {HTMLElement} btn
     * @param {MouseEvent}  event
     */
    function createRipple(btn, event) {
        // Remove any existing ripples
        const existing = btn.querySelector('.ripple');
        if (existing) existing.remove();

        const rect   = btn.getBoundingClientRect();
        const size   = Math.max(rect.width, rect.height) * 2;
        const x      = event.clientX - rect.left - size / 2;
        const y      = event.clientY - rect.top  - size / 2;

        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        ripple.style.cssText = `
            width:  ${size}px;
            height: ${size}px;
            left:   ${x}px;
            top:    ${y}px;
        `;

        btn.appendChild(ripple);

        // Remove after animation
        ripple.addEventListener('animationend', () => ripple.remove(), { once: true });
    }

    /**
     * Create the floating toggle button and inject into the DOM.
     */
    function createToggleButton() {
        // Avoid duplicates
        if (document.getElementById('theme-toggle-btn')) return;

        const currentTheme = document.documentElement.getAttribute('data-theme') || DEFAULT_THEME;

        const btn = document.createElement('button');
        btn.id              = 'theme-toggle-btn';
        btn.setAttribute('aria-label', TITLES[currentTheme]);
        btn.setAttribute('title',      TITLES[currentTheme]);
        btn.setAttribute('type',       'button');

        // Icon span
        const iconEl = document.createElement('span');
        iconEl.classList.add('btn-icon');
        iconEl.textContent = ICONS[currentTheme];
        btn.appendChild(iconEl);

        // Ripple on click
        btn.addEventListener('click', function (e) {
            createRipple(this, e);
            // Small delay to show ripple before theme switches
            requestAnimationFrame(() => toggleTheme());
        });

        document.body.appendChild(btn);
    }

    /* ----------------------------------------------------------
       LISTEN TO OS PREFERENCE CHANGES
    ---------------------------------------------------------- */
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
            // Only respond if user hasn't explicitly saved a preference
            if (!localStorage.getItem(STORAGE_KEY)) {
                applyTheme(e.matches ? DARK_THEME : LIGHT_THEME);
            }
        });
    }

    /* ----------------------------------------------------------
       INITIALISE
    ---------------------------------------------------------- */

    /**
     * Main init — called on DOMContentLoaded.
     * By this time, theme is already applied from the head script.
     * We only need to create the button here.
     */
    function init() {
        // Apply theme (may already be set, but ensure it's applied)
        const theme = getPreferredTheme();
        applyTheme(theme);

        // Create floating button
        createToggleButton();
    }

    // If DOM is already ready, run immediately; otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    /* ----------------------------------------------------------
       EXPOSE PUBLIC API (optional — for other scripts)
    ---------------------------------------------------------- */
    window.HaTheme = {
        toggle:     toggleTheme,
        apply:      applyTheme,
        getCurrent: function () {
            return document.documentElement.getAttribute('data-theme') || DEFAULT_THEME;
        },
        DARK:  DARK_THEME,
        LIGHT: LIGHT_THEME,
    };

})();
