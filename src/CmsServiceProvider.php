<?php

namespace WebModularity\LaravelCms;

use Route;
use View;
use Auth;
use Blade;
use Illuminate\Support\ServiceProvider;
use WebModularity\LaravelContact\AddressState;
use WebModularity\LaravelLog\LogRequestMethod;
use WebModularity\LaravelLog\LogServiceProvider;
use WebModularity\LaravelUser\LogUser;
use WebModularity\LaravelUser\LogUserAction;
use WebModularity\LaravelUser\User;
use WebModularity\LaravelUser\UserServiceProvider;
use WebModularity\LaravelUser\UserSocialProvider;
use Yajra\Datatables\ButtonsServiceProvider;
use Yajra\Datatables\DatatablesServiceProvider;
use JeroenNoten\LaravelAdminLte\ServiceProvider as AdminLteServiceProvider;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;
use Maatwebsite\Excel\ExcelServiceProvider;
use Sineld\BladeSet\BladeSetServiceProvider;
use WebModularity\LaravelUser\UserRole;

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

        // Register AdminLTE
        $this->app->register(ExcelServiceProvider::class);

        // Blade Set
        $this->app->register(BladeSetServiceProvider::class);

        // Config
        $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'wm.cms');
    }

    public function boot()
    {
        // Config
        $this->publishes([__DIR__ . '/../config/cms.php' => config_path('wm/cms.php')], 'config');

        $this->loadViews();

        // Route Binding
        // User Recycle
        Route::bind('recycledUser', function ($id) {
            return User::onlyTrashed()->find($id);
        });

        // View Composers
        //AdminLTE
        View::composer('wmcms::page', AdminLteComposer::class);
        // recentLogins
        View::composer('wmcms::navbar.user-menu', function ($view) {
            $view->with(
                'activeUserRecentLogins',
                LogUser::where('user_id', Auth::id())
                    ->with(['logRequest', 'logRequest.ipAddress', 'socialProvider'])
                    ->logins()
                    ->latest()
                    ->recentDays(30)
                    ->limit(3)
                    ->get()
            );
        });
        // States
        View::composer(['wmcms::partials.form.address'], function ($view) {
            $view->with(
                'stateList',
                AddressState::select(['id', 'iso'])
                    ->where('country_id', 1)
                    ->orderBy('iso', 'asc')
                    ->get()
                    ->toArray()
            );
        });
        // User Social Providers
        View::composer(['wmcms::users.edit', 'wmcms::log-user.filter'], function ($view) {
            $view->with('socialProviders', UserSocialProvider::isActive()->get());
        });
        // User Roles
        View::composer(['wmcms::users.form', 'wmcms::users.filter'], function ($view) {
            $view->with(
                'userRoles',
                UserRole::select(['id', 'slug'])
                    ->where('id', '<=', Auth::user()->role_id)
                    ->orderBy('id', 'asc')
                    ->get()
                    ->toArray()
            );
        });
        // Log Request Methods
        View::composer(['wmcms::log-user.filter'], function ($view) {
            $view->with(
                'logRequestMethods',
                LogRequestMethod::select(['id', 'method'])
                    ->orderBy('method', 'asc')
                    ->get()
                    ->toArray()
            );
        });
        // Log User Actions
        View::composer(['wmcms::log-user.filter'], function ($view) {
            $view->with(
                'logUserActions',
                LogUserAction::select(['id', 'slug'])
                    ->orderBy('slug', 'asc')
                    ->get()
                    ->toArray()
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
