<?php

namespace WebModularity\LaravelCms;

use View;
use Auth;
use Blade;
use Illuminate\Support\ServiceProvider;
use WebModularity\LaravelContact\AddressState;
use WebModularity\LaravelLog\LogServiceProvider;
use WebModularity\LaravelUser\LogUser;
use WebModularity\LaravelUser\UserServiceProvider;
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
        $this->loadBlade();

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
            $view->with('stateList', AddressState::select(['id', 'iso'])->where('country_id', 1)->orderBy('iso', 'asc')->get()->toArray());
        });
        // User Roles
        View::composer(['wmcms::users.form'], function ($view) {
            $view->with(
                'userRoles',
                UserRole::select(['id', 'slug'])
                    ->where('id', '<=', Auth::user()->role_id)
                    ->orderBy('id', 'asc')
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

    private function loadBlade()
    {
        Blade::directive('dtdefaults', function ($dtTableIds) {
            $inits = [];
            $dtTableIds = $dtTableIds ?: 'dataTableBuilder';
            $tableIds = explode(',', $dtTableIds);
            foreach ($tableIds as $tableId) {
                $tableId = trim(trim($tableId), "'");
                $inits[] = "$('#".$tableId."_filter').appendTo($('#".$tableId."').closest('div.box').find('div.box-header div.box-tools'));";
            }
            $script = "<script>
    $.extend(true, $.fn.dataTable.defaults, {
        buttons: [],
        language: {
            search: \"<div class='has-feedback'>_INPUT_<span class='glyphicon glyphicon-search form-control-feedback'></span></div>\",
            searchPlaceholder: \"Search...\",
            lengthMenu: \"Results per page: _MENU_\"
        },
        dom: \"<'row'<'col-sm-9'B><'col-sm-3'<'pull-right'l>f>>\" +
        \"<'row'<'col-sm-12'tr>>\" +
        \"<'row'<'col-sm-5'i><'col-sm-7'p>>\",
        initComplete: function() {";
            foreach ($inits as $init) {
                $script .= $init . "\n";
            }
        $script .= "}
    });
</script>";

        return $script;
        });
    }
}
