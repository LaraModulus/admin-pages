<?php

namespace LaraMod\Admin\Pages;

use Illuminate\Support\ServiceProvider;

class AdminPagesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . '/views', 'adminpages');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/laramod/admin/pages'),
        ]);
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes.php';
    }
}