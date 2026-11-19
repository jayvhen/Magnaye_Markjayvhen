<?php

namespace App\Providers;

use App\Models\Shoe;
use App\Policies\V1\ShoePolicy;
use Illuminate\Support\Facades\Gate;
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
        // Shoe models are protected by ShoePolicy
        Gate::policy(Shoe::class, ShoePolicy::class);
    }
}
