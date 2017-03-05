<?php

namespace WebModularity\LaravelCms;

use View;
use Auth;
use Illuminate\Support\ServiceProvider;
use WebModularity\LaravelLog\LogServiceProvider;
use WebModularity\LaravelUser\LogUser;
use WebModularity\LaravelUser\UserServiceProvider;
use Yajra\Datatables\ButtonsServiceProvider;
use Yajra\Datatables\DatatablesServiceProvider;
use JeroenNoten\LaravelAdminLte\ServiceProvider as AdminLteServiceProvider;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;

class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register LaravelUser Service Provider
        $this->app->register(UserServiceProvider::class);

        // Register Datatables Service Provider
        $this->app->register(DatatablesServiceProvider::class);

        // Register Datatables Buttons Service Provider
        $this->app->register(ButtonsServiceProvider::class);

        // Register LaravelLog Service Provider
        $this->app->register(LogServiceProvider::class);

        // Register AdminLTE
        $this->app->register(AdminLteServiceProvider::class);

        // Config
        $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'wm.cms');
    }

    public function boot()
    {
        // Config
        $this->publishes([__DIR__ . '/../config/cms.php' => config_path('wm/cms.php')], 'config');

        $this->loadViews();

        $this->app->make('router')->group(['namespace' => 'WebModularity\LaravelCms\Http\Controllers'], function () {
            $this->app->make('router')->resource('user-log', 'UserLogController', ['only' => [
                'index', 'show'
            ]]);
        });

        // View Composers
        //AdminLTE
        View::composer('adminlte::page', AdminLteComposer::class);
        // recentLogins
        View::composer('wmcms::navbar.user-menu', function ($view) {
            $view->with(
                'activeUserRecentLogins',
                LogUser::where('user_id', Auth::user()->id)
                    ->with(['logRequest', 'logRequest.ipAddress', 'socialProvider'])
                    ->logins()
                    ->latest()
                    ->recentDays(30)
                    ->limit(3)
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
