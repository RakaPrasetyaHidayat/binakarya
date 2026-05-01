<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Book;
use App\Models\Page;
use App\Models\Service;
use App\Policies\PostPolicy;
use App\Policies\BookPolicy;
use App\Policies\PagePolicy;
use App\Policies\ServicePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogAuthenticatedLogin;
use App\Listeners\LogAuthenticatedLogout;
use App\Models\Menu;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        \Illuminate\Support\Facades\Vite::useHotFile(storage_path('vite.hot.override'));
        
        /**
         * Register Authentication Listeners
         */
        Event::listen(Login::class, LogAuthenticatedLogin::class);
        Event::listen(Logout::class, LogAuthenticatedLogout::class);

        /**
         * Register Policies
         */
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Book::class, BookPolicy::class);
        Gate::policy(Page::class, PagePolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);

        /**
         * Rate Limiting Configuration
         */
        // Login: max 5 attempts per minute per IP (brute force protection)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Admin Dashboard: 60 requests per minute per authenticated user
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Contact form: 3 submissions per hour per IP
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(3)->by($request->ip());
        });

        // Subscribe: 5 per hour per IP
        RateLimiter::for('subscribe', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // API: 60 requests per minute
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

