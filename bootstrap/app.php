<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /*
         * LocaleMiddleware — reads session/cookie locale and sets:
         *   • App::setLocale()
         *   • Carbon::setLocale()
         *   • View::share('currentLocale', 'currentDir', 'currentLang')
         *
         * This runs on every web request so the language is always correct.
         */
        $middleware->web(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
            \App\Http\Middleware\OptimizeResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // معالجة خطأ حجم الملف الكبير بشكل أنيق بدل صفحة 413
        $exceptions->render(function (
            \Illuminate\Http\Exceptions\PostTooLargeException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'حجم الملف المرفوع أكبر من الحد المسموح به.',
                ], 413);
            }

            return redirect()->back()->with(
                'error',
                'حجم الملف أكبر من الحد المسموح به (الحد الأقصى 20 ميغابايت). يرجى تقليص الملف وإعادة المحاولة.'
            );
        });
    })->create();
