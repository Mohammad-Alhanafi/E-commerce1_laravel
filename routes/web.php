<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Http\Request;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LanguageController;

/* =========================
   LANGUAGE SWITCHER
   Switches app locale and stores in session + cookie.
   Redirects back to the previous page.
========================= */
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang.switch')
    ->where('locale', 'ar|en|fr');


/* =========================
   HOME
========================= */
Route::get('/', [HomeController::class, 'index'])->name('home');

/* =========================
   ADMIN
========================= */
Route::prefix('admin')->group(function () {

    Route::get('/', [AdminPanelController::class, 'dashboardStatus']);

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::delete('/settings/delete-number', [SettingController::class, 'deleteNumber'])->name('admin.settings.delete_number');

    // Theme Management Routes
    Route::post('/themes/generate-colors', [App\Http\Controllers\Admin\ThemeController::class, 'generateColors'])->name('admin.themes.generate');
    Route::post('/themes/generate-from-background', [App\Http\Controllers\Admin\ThemeController::class, 'generateFromBackground'])->name('admin.themes.generate.background');
    Route::post('/themes/import', [App\Http\Controllers\Admin\ThemeController::class, 'import'])->name('admin.themes.import');
    Route::post('/themes/{theme}/activate', [App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('admin.themes.activate');
    Route::post('/themes/{theme}/duplicate', [App\Http\Controllers\Admin\ThemeController::class, 'duplicate'])->name('admin.themes.duplicate');
    Route::get('/themes/{theme}/export', [App\Http\Controllers\Admin\ThemeController::class, 'export'])->name('admin.themes.export');
    Route::resource('themes', App\Http\Controllers\Admin\ThemeController::class)->names('admin.themes');

    Route::get('/ajax/sales-chart', [AdminPanelController::class, 'ajaxChart']);
    Route::get('/ajax/status', [AdminPanelController::class, 'ajaxStatus']);
    Route::get('/ajax/top-products', [AdminPanelController::class, 'ajaxTopProducts']);
Route::get('/ajax/latest-orders', [AdminPanelController::class, 'ajaxLatestOrders']);
Route::get('/ajax/low-stock', [AdminPanelController::class, 'ajaxLowStock']);
});

/* =========================
   CATEGORIES
========================= */
Route::resource('category', CategoryController::class);

Route::post('/admin/category/update-image/{id}', [CategoryController::class, 'updateImage']);
Route::delete('/admin/category/delete/{id}', [CategoryController::class, 'destroy']);
Route::post('/admin/category/update-text/{id}', [CategoryController::class, 'updateText']);
Route::get('/admin/category/create-fast', [CategoryController::class, 'storeFast'])
    ->name('category.fast_store');

/* =========================
   PRODUCTS
========================= */
Route::resource('products', ProductController::class);

Route::get('/item/{id}', [ProductController::class, 'showClient'])
    ->name('product.details');


Route::prefix('comments')->group(function () {

    Route::post('/store', [CommentController::class, 'store'])->name('comments.store');

    Route::post('/{id}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    Route::post('/{id}/like', [CommentController::class, 'like'])->name('comments.like');

    Route::delete('/{id}', [CommentController::class, 'destroy'])->name('comments.delete');
});

/* =========================
   ORDERS
========================= */
Route::resource('orders', OrderController::class);
Route::get('/admin/ajax/latest-orders', [OrderController::class, 'latestOrdersData']);

/* =========================
   USERS
========================= */
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/filter', [UserController::class, 'filter'])->name('users.filter');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

/* =========================
   SLIDERS
========================= */
Route::resource('sliders', SliderController::class);

Route::post('/settings/update-logo', [SliderController::class, 'updateLogo'])
    ->name('settings.updateLogo');

/* =========================
   CART
========================= */
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

/* =========================
   CHECKOUT & ACCOUNT
========================= */
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::get('/account', function () {
    if (! Auth::check()) {
        return redirect()->route('login.page');
    }

    $orders = \App\Models\Order::with('products')
        ->where('user_id', Auth::id())
        ->latest()
        ->paginate(8);

    return view('frontend.account', [
        'user' => Auth::user(),
        'orders' => $orders,
    ]);
})->name('account');

Route::post('/account', [AccountController::class, 'update'])->name('account.update');

/* =========================
   AUTH PAGE (صفحة الدخول والتسجيل)
========================= */
Route::get('/auth-page', function () {
    return view('auth.register'); 
})->name('auth.page');

Route::get('/login-client', function () {
    return view('auth.register');
})->name('login.page');

Route::get('/register-client', function () {
    return view('auth.register');
})->name('register.page');

Route::post('/login-client', [UserController::class, 'loginClient'])
    ->name('client.login');

Route::post('/register-client', [UserController::class, 'registerClient'])
    ->name('client.register');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/comments/fetch', [CommentController::class, 'fetch']);
Route::put('/comments/{id}', [CommentController::class, 'update']);
Route::post('/account/avatar', [AccountController::class, 'updateAvatar'])->name('account.avatar.update');

Route::prefix('variants')->group(function () {
    Route::get('/product/{product}', [VariantController::class, 'index']);
    Route::post('/', [VariantController::class, 'store']);
    Route::put('/{variant}', [VariantController::class, 'update']);
    Route::delete('/{variant}', [VariantController::class, 'destroy']);
});

Route::post('/forgot-password/send-code', [UserController::class, 'sendResetCode'])
    ->name('password.send-code')
    ->middleware('throttle:3,1'); 

Route::post('/forgot-password/reset', [UserController::class, 'resetPasswordWithCode'])
    ->name('password.reset')
    ->middleware('throttle:5,1');





use App\Models\User;

Route::get('/make-me-admin/{email}', function ($email) {
    $user = User::where('email', $email)->first();

    if (!$user) {
        return 'User not found!';
    }

    $user->update(['role' => 'admin']);

    return "Account {$email} has been successfully promoted to Admin!";
});


Route::get('/run-migrations-now', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('migrate', ['--force' => true]);

        return 'Success: Database migrated and all caches cleared successfully!';
    } catch (\Exception $e) {
        return 'Error executing migration: ' . $e->getMessage();
    }
});