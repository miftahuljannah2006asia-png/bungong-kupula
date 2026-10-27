<?php

namespace App\Providers;

use App\Services\ContentRepository;
use App\Services\ProductCatalog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ContentRepository::class);
        $this->app->singleton(ProductCatalog::class);
    }

    public function boot(): void
    {
        // Aman untuk MySQL/MariaDB lama di shared hosting (batas panjang kunci index).
        Schema::defaultStringLength(191);

        // Data ringkas resep dipakai laci "Resep Disimpan" di semua halaman.
        View::composer('layouts.app', function ($view) {
            $view->with('recipeIndex', app(ContentRepository::class)->recipeIndex());
        });
    }
}
