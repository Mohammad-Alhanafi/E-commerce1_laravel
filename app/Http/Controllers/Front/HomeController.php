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
    $sliders = Slider::where('status', 'active')
        ->orderBy('order', 'asc')
        ->get();

    $categories = Category::where('is_active', 1)
        ->orderBy('sort_order', 'asc')
        ->with(['products' => function ($q) {
            $q->where('status', 'active')->oldest()->limit(1);
        }])
        ->get();

    $settings = DB::table('settings')->pluck('value', 'key')->toArray();

    $comments = Comment::whereNull('parent_id')
        ->with(['replies.likes', 'likes'])
        ->latest()
        ->get();

    $featuredProducts = Product::where('status', 'active')
        ->latest()
        ->take(8)
        ->get();

    return view('frontend.home', compact(
        'sliders', 'categories', 'featuredProducts', 'comments', 'settings'
    ));
}
}