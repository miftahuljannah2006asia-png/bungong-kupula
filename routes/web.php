<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    Route::get('/pustaka-rasa', 'recipes')->name('recipes.index');
    Route::get('/pustaka-rasa/{slug}', 'recipe')->where('slug', '[a-z0-9\-]+')->name('recipes.show');

    Route::get('/peta-wilayah', 'regions')->name('regions');
    Route::get('/pasar-oleh-oleh', 'market')->name('market');

    Route::get('/hikayat', 'stories')->name('stories.index');
    Route::get('/hikayat/{slug}', 'story')->where('slug', '[a-z0-9\-]+')->name('stories.show');

    Route::get('/kontak', 'contact')->name('contact');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
});

Route::post('/pesanan', [OrderController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('orders.store');

Route::post('/pesan', [MessageController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('messages.store');

/*
| Area admin: /admin  (login dengan ADMIN_EMAIL + ADMIN_PASSWORD_HASH di .env)
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('kuliner/impor', [AdminRecipeController::class, 'import'])->name('recipes.import');
        Route::resource('kuliner', AdminRecipeController::class)
            ->except('show')
            ->parameters(['kuliner' => 'recipe'])
            ->names('recipes');

        Route::resource('produk', AdminProductController::class)
            ->except('show')
            ->parameters(['produk' => 'product'])
            ->names('products');
    });
});
