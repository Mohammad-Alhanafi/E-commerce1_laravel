<?php
/*
 * ============================================================
 *  LanguageController.php — Honey Abayah E-Commerce
 *  ────────────────────────────────────────────────────────────
 *  PURPOSE:
 *    Handles the locale switch request from the language switcher.
 *    Validates the requested locale, stores it in session + cookie,
 *    then redirects back to the previous page.
 *
 *  ROUTE:
 *    GET /lang/{locale}
 *    Name: lang.switch
 *
 *  COOKIE LIFETIME:
 *    365 days — so locale persists even after session expires.
 * ============================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Supported locales list.
     */
    protected array $supportedLocales = ['ar', 'en', 'fr'];

    /**
     * Switch the application locale.
     *
     * @param  string  $locale   — e.g. 'ar', 'en', 'fr'
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(string $locale): RedirectResponse
    {
        // ── Validate locale ──────────────────────────────────────
        if (!in_array($locale, $this->supportedLocales, true)) {
            // Invalid locale → fallback to Arabic
            $locale = 'ar';
        }

        // ── Store in session (immediate effect) ──────────────────
        session(['locale' => $locale]);

        // ── Store in cookie (persists across sessions) ───────────
        // Cookie lifetime: 365 days (in minutes)
        $cookieLifetime = 365 * 24 * 60;

        // ── Set app locale for this request ─────────────────────
        App::setLocale($locale);

        // ── Redirect back ────────────────────────────────────────
        return redirect()
            ->back()
            ->withCookie(cookie('locale', $locale, $cookieLifetime));
    }
}
