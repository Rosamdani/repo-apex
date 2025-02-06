<?php

namespace App\Providers;

use App\Models\PaketTryout;
use App\Models\UserTryouts;
use App\Observers\UserAccessPaketObserver;
use App\Observers\UserTryoutsObserver;
use Illuminate\Support\ServiceProvider;

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
        UserTryouts::observe(UserTryoutsObserver::class);
        PaketTryout::observe(UserAccessPaketObserver::class);
    }
}
