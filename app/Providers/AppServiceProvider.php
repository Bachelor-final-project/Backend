<?php

namespace App\Providers;

use App\Models\Proposal;
use Illuminate\Cookie\Middleware\EncryptCookies;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;
use App\Models\User;
use App\Observers\ProposalObserver;

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
        // Proposal::observe(ProposalObserver::class);
        Vite::prefetch(concurrency: 3);
    }
}
