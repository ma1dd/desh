<?php

namespace App\Providers;

use App\Models\AnalyticalSession;
use App\Policies\AnalyticalSessionPolicy;
use Illuminate\Pagination\Paginator;
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
        Gate::policy(AnalyticalSession::class, AnalyticalSessionPolicy::class);

        // Нормализуем вид пагинации под Bootstrap 5
        Paginator::useBootstrapFive();

        Gate::before(function ($user, string $ability) {
            return ($user?->role?->name === 'admin') ? true : null;
        });

        Gate::define('view-admin-panel', function ($user) {
            return $user?->role?->name === 'admin';
        });
    }
}
