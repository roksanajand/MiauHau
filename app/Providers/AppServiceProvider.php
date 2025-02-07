<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Footer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Automatyczne wstrzykiwanie danych do stopki
        View::composer('components.footer', function ($view) {
            $contactInfo = Footer::getAdminContactInfo();

            $view->with('contactInfo', [
                'name' => $contactInfo->name ?? 'Default Admin',
                'email' => $contactInfo->email ?? 'default@example.com',
            ]);
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
