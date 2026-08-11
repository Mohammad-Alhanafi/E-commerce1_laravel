<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Category;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    // فرض استخدام HTTPS دائمًا عند العمل بالإنتاج (يحل مشكلة Mixed Content خلف الـ Proxy)
    if ($this->app->environment('production')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }

    \Illuminate\Pagination\Paginator::useBootstrap();

    // Share settings globally across all views (optimized with persistent caching)
    View::composer('*', function ($view) {
        $settings = \Illuminate\Support\Facades\Cache::remember('global_app_settings', 86400, function () {
            try {
                return DB::table('settings')->pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });

        $view->with('settings', $settings);
    });

    // Share categories only with the header view where they are needed (optimized eager loading & caching)
    View::composer('admin.header', function ($view) {
        $categories = \Illuminate\Support\Facades\Cache::remember('header_categories_tree', 86400, function () {
            try {
                return Category::select('id', 'name', 'is_active', 'sort_order')
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'asc')
                    ->with(['products' => function ($q) {
                        $q->select('id', 'name', 'category_id')->where('status', 'active');
                    }])
                    ->get();
            } catch (\Exception $e) {
                return collect();
            }
        });

        $view->with('categories', $categories);
    });
}


}