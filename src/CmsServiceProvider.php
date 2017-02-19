<?php

namespace WebModularity\LaravelCms;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register LaravelAuth Service Provider
        $this->app->register('WebModularity\LaravelAuth\AuthServiceProvider');

        // Register LaravelLog Service Provider
        $this->app->register('WebModularity\LaravelLog\LogServiceProvider');

        // Register AdminLte Service Provider
        $this->app->register('JeroenNoten\LaravelAdminLte\ServiceProvider');

        // Config
        $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'wm.cms');
    }

    public function boot()
    {
        // Config
        $this->publishes([__DIR__ . '/../config/cms.php' => config_path('wm/cms.php')], 'config');

        // Migrations
        //$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
