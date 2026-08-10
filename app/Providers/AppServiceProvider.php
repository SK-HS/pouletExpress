<?php

namespace App\Providers;

use App\Models\CartService as ModelsCartService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\CartService;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Schema::defaultStringLength(191);
         View::composer('*', function ($view) {
        $view->with('cartCount', app(CartService::class)->count());
    });
    }
}
