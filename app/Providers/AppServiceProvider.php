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
        \Illuminate\Pagination\Paginator::useBootstrap();

        // Share settings globally across all views (optimized with request-level caching and migration safety)
        View::composer('*', function ($view) {
            static $settings = null;

            if ($settings === null) {
                try {
                    $settings = DB::table('settings')->pluck('value', 'key')->toArray();
                } catch (\Exception $e) {
                    $settings = [];
                }
            }

            $view->with('settings', $settings);
        });

        // Share categories only with the header view where they are needed
        View::composer('admin.header', function ($view) {
            try {
                $categories = Category::with('products')->get();
            } catch (\Exception $e) {
                $categories = collect();
            }

            $view->with('categories', $categories);
        });
    }




}