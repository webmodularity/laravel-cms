<?php

namespace WebModularity\LaravelCms;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

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

    public function boot(Dispatcher $events)
    {
        // Config
        $this->publishes([__DIR__ . '/../config/cms.php' => config_path('wm/cms.php')], 'config');

        // Migrations
        //$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
