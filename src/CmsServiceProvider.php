<?php

namespace WebModularity\LaravelCms;

use Illuminate\Support\ServiceProvider;
use View;
use WebModularity\LaravelAuth\User\LogUser;

class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register LaravelAuth Service Provider
        $this->app->register('WebModularity\LaravelAuth\AuthServiceProvider');

        // Register LaravelLog Service Provider
        $this->app->register('WebModularity\LaravelLog\LogServiceProvider');

        // Config
        $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'wm.cms');
    }

    public function boot()
    {
        // Config
        $this->publishes([__DIR__ . '/../config/cms.php' => config_path('wm/cms.php')], 'config');

        // View Composers
        // recentLogins
        View::composer('vendor.adminlte.partials.navbar.user-menu', function ($view) {
            $view->with('activeUserRecentLogins', LogUser::recentLogins(3)->get());
        });
        // Migrations
        //$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
