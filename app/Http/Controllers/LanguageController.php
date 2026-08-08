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
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale   — e.g. 'ar', 'en', 'fr'
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, string $locale)
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
        $cookie = cookie('locale', $locale, $cookieLifetime);

        // ── Set app locale for this request ─────────────────────
        App::setLocale($locale);

        // ── If AJAX / JSON request, return JSON without refresh ──
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'locale'  => $locale,
                'dir'     => $locale === 'ar' ? 'rtl' : 'ltr',
            ])->withCookie($cookie);
        }

        // ── Redirect back for standard browser navigation ────────
        return redirect()
            ->back()
            ->withCookie($cookie);
    }
}
