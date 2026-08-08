<?php

namespace App\Http\Controllers\Front;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = \Illuminate\Support\Facades\Cache::remember('home_sliders', 86400, function () {
            return Slider::select('id', 'title', 'image', 'link', 'status', 'order')
                ->where('status', 'active')
                ->orderBy('order', 'asc')
                ->get();
        });

        $categories = \Illuminate\Support\Facades\Cache::remember('home_categories_tree', 86400, function () {
            return Category::select('id', 'name', 'image', 'is_active', 'sort_order')
                ->where('is_active', 1)
                ->orderBy('sort_order', 'asc')
                ->with(['products' => function ($q) {
                    $q->select('id', 'name', 'image', 'price', 'category_id')
                      ->where('status', 'active')
                      ->oldest()
                      ->limit(1);
                }])
                ->get();
        });

        $settings = \Illuminate\Support\Facades\Cache::remember('global_app_settings', 86400, function () {
            return DB::table('settings')->pluck('value', 'key')->toArray();
        });

        $comments = Comment::whereNull('parent_id')
            ->with(['replies.likes', 'likes'])
            ->latest()
            ->take(15)
            ->get();

        $featuredProducts = \Illuminate\Support\Facades\Cache::remember('home_featured_products', 86400, function () {
            return Product::select('id', 'name', 'image', 'price', 'category_id', 'status', 'created_at')
                ->where('status', 'active')
                ->with('category:id,name')
                ->latest()
                ->take(8)
                ->get();
        });

        return view('frontend.home', compact(
            'sliders', 'categories', 'featuredProducts', 'comments', 'settings'
        ));
    }
}