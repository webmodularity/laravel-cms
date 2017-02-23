<?php

namespace WebModularity\LaravelCms;

use Auth;
use Illuminate\Support\ServiceProvider;
use View;
use WebModularity\LaravelUser\LogUser;

class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register LaravelAuth Service Provider
        $this->app->register('WebModularity\LaravelUser\UserServiceProvider');

        // Register LaravelLog Service Provider
        $this->app->register('WebModularity\LaravelLog\LogServiceProvider');

        // Config
        $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'wm.cms');
    }

    public function boot()
    {
        // Config
        $this->publishes([__DIR__ . '/../config/cms.php' => config_path('wm/cms.php')], 'config');

        $this->loadViews();

        // View Composers
        // recentLogins
        View::composer('wmcms::navbar.user-menu', function ($view) {
            $view->with(
                'activeUserRecentLogins',
                LogUser::where('user_id', Auth::user()->id)
                    ->with('logRequest.urlPath')
                    ->recentLogins(3)
                    ->get()
            );
        });
        // Migrations
        //$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    private function loadViews()
    {
        $viewsPath = __DIR__ . '/../resources/views';
        $this->loadViewsFrom($viewsPath, 'wmcms');
        $this->publishes([$viewsPath => base_path('resources/views/vendor/wmcms')], 'views');
    }
}
