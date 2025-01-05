<?php

namespace App\Providers;

use Illuminate\Cookie\Middleware\EncryptCookies;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;
use App\Models\User;

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
        EncryptCookies::except('locale');
        EncryptCookies::except('laravel_session');
        User::observe(UserObserver::class);
        Vite::prefetch(concurrency: 3);
    }
}
