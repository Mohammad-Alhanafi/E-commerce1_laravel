<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Theme;

class LocaleMiddleware
{
    /**
     * Supported locales and their HTML direction attribute.
     */
    protected array $locales = [
        'ar' => 'rtl',
        'en' => 'ltr',
        'fr' => 'ltr',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Resolve active locale
        $locale = $this->resolveLocale($request);

        // 2. Set application locale
        App::setLocale($locale);

        // 3. Set Carbon locale for date/time formatting
        try {
            if (class_exists(\Carbon\Carbon::class)) {
                \Carbon\Carbon::setLocale($locale);
            }
        } catch (\Throwable $e) {
            // Non-critical fallback
        }

        // 4. Determine direction
        $dir = $this->locales[$locale] ?? 'rtl';

        // 5. Share locale & theme settings globally with all Blade views
        $themeColors = Theme::getActiveColors();

        View::share('currentLocale', $locale);
        View::share('currentDir',    $dir);
        View::share('currentLang',   $locale);
        View::share('themeColors',   $themeColors);

        return $next($request);
    }

    /**
     * Resolve the locale priority:
     * URL query parameter (?lang= or ?locale=) -> session -> cookie -> app config default.
     */
    protected function resolveLocale(Request $request): string
    {
        // Check URL parameter (?lang= or ?locale=)
        $urlLocale = $request->query('lang') ?? $request->query('locale');
        if ($urlLocale && $this->isValidLocale($urlLocale)) {
            session(['locale' => $urlLocale]);
            return $urlLocale;
        }

        // Check session
        if (session()->has('locale')) {
            $locale = session('locale');
            if ($this->isValidLocale($locale)) {
                return $locale;
            }
        }

        // Check cookie fallback
        $cookieLocale = $request->cookie('locale');
        if ($cookieLocale && $this->isValidLocale($cookieLocale)) {
            session(['locale' => $cookieLocale]);
            return $cookieLocale;
        }

        // Default app locale fallback
        $default = Config::get('app.locale', 'ar');
        return $this->isValidLocale($default) ? $default : 'ar';
    }

    /**
     * Check if given locale code is supported.
     */
    protected function isValidLocale(string $locale): bool
    {
        return array_key_exists($locale, $this->locales);
    }
}
