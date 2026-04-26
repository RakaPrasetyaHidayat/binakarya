<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $settings = Setting::pluck('value', 'key');
                $view->with('siteSettings', $settings);
            } catch (\Exception) {
                $view->with('siteSettings', collect());
            }

            try {
                $publicMenus = Menu::with('children')
                    ->where('is_active', true)
                    ->whereNull('parent_id')
                    ->orderBy('order')
                    ->get();
                $view->with('publicMenus', $publicMenus);
            } catch (\Exception) {
                $view->with('publicMenus', collect());
            }
        });
    }
}
