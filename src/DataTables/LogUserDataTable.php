<?php

namespace WebModularity\LaravelCms\DataTables;

use Carbon\Carbon;
use WebModularity\LaravelUser\LogUser;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Auth;
use DB;

class LogUserDataTable extends CmsDataTable
{
    public static $actionView = 'wmcms::log-user.actions.show-modal';
    public static $order = [[0, "desc"]];
    public static $exportFilename = 'user';
    public static $responsive = true;
    public static $pageLength = 50;

    /**
     * Build DataTable class.
     *
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', $this->getActionView())
            ->editColumn('ip_address', function (LogUser $logUser) {
                return isset($logUser->logRequest->ipAddress) && !empty($logUser->logRequest->ipAddress->ip)
                    ? $logUser->logRequest->ipAddress->ip
                    : null;
            })
            ->filterColumn('ip_address', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    DB::raw("INET6_NTOA(ip)"),
                    static::getColumnFilter($keyword, ['ip'])
                );
            })
            ->orderColumn('ip_address', 'ip $1')
            ->filterColumn('user.person.email', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    'people.email',
                    static::getColumnFilter($keyword, ['user'])
                );
            })
            ->filterColumn('userAction.slug', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    'log_user_actions.slug',
                    static::getColumnFilter($keyword, ['action'])
                );
            })
            ->filterColumn('logRequest.requestMethod.method', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    'log_request_methods.method',
                    static::getColumnFilter($keyword, ['method'])
                );
            })
            ->filterColumn('logRequest.urlPath.url_path', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    'log_url_paths.url_path',
                    static::getColumnFilter($keyword, ['url'])
                );
            })
            ->filterColumn('logRequest.session_id', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    'log_requests.session_id',
                    static::getColumnFilter($keyword, ['session'])
                );
            })
            ->editColumn('created_at', function (LogUser $logUser) {
                return $logUser->created_at ? with(new Carbon($logUser->created_at))->format('m/d/Y h:i:sa') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                return static::filterCreatedAt($query, $keyword, 'log_users');
            })
            ->orderColumn('created_at', '`log_users`.created_at $1')
            ->addColumn('social_provider_name', function (LogUser $logUser) {
                return !is_null($logUser->socialProvider) ? $logUser->socialProvider->getName() : null;
            })
            ->filterColumn('social_provider_name', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    'user_social_providers.slug',
                    static::getColumnFilter($keyword, ['social'])
                );
            })
            ->orderColumn('social_provider_name', 'user_social_providers.slug $1')
            ->rawColumns(['action']);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = LogUser::query()
            ->select('log_users.*')
            ->leftJoin('log_user_actions', 'log_users.user_action_id', '=', 'log_user_actions.id')
            ->leftJoin('log_requests', 'log_users.log_request_id', '=', 'log_requests.id')
            ->leftJoin('log_request_methods', 'log_requests.request_method_id', '=', 'log_request_methods.id')
            ->leftJoin('log_url_paths', 'log_requests.url_path_id', '=', 'log_url_paths.id')
            ->leftJoin('log_ip_addresses', 'log_requests.ip_address_id', '=', 'log_ip_addresses.id')
            ->leftJoin('user_social_providers', 'log_users.social_provider_id', '=', 'user_social_providers.id')
            ->leftJoin('users', 'log_users.user_id', '=', 'users.id')
            ->leftJoin('people', 'users.person_id', '=', 'people.id')
            ->whereHas('user', function ($query) {
                $query->visibleByRole(Auth::user()->role_id);
            })
            ->with([
                'logRequest',
                'logRequest.ipAddress',
                'logRequest.urlPath',
                'logRequest.requestMethod',
                'user.person',
                'userAction',
                'socialProvider'
            ])
            ->orderBy('log_users.id', 'desc');

        return $this->applyScopes($query);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            new Column(
                [
                    'data' => 'created_at',
                    'title' => 'Time',
                    'className' => 'max-desktop'
                ]
            ),
            new Column(
                [
                    'data' => 'user.person.email',
                    'title' => 'User',
                    'className' => 'max-desktop'
                ]
            ),
            new Column(
                [
                    'data' => 'ip_address',
                    'title' => 'IP',
                    'className' => 'min-tablet-l'
                ]
            ),
            new Column(
                [
                    'data' => 'user_action.slug',
                    'name' => 'userAction.slug',
                    'title' => 'Action',
                    'className' => 'min-tablet-l'
                ]
            ),
            new Column(
                [
                    'data' => 'log_request.request_method.method',
                    'name' => 'logRequest.requestMethod.method',
                    'title' => 'Method',
                    'className' => 'desktop'
                ]
            ),
            new Column(
                [
                    'data' => 'log_request.url_path.url_path',
                    'name' => 'logRequest.urlPath.url_path',
                    'title' => 'URL',
                    'className' => 'desktop',
                    'render' => 'function() {
                                    var max = 25;
                                    if ( type === \'display\' && data.length > max) {
                                        return \'&#8230;\' + data.substr(-max);
                                    }
                                    return data;
                                }'
                ]
            ),
            new Column(
                [
                    'data' => 'social_provider_name',
                    'title' => 'Social',
                    'className' => 'desktop',
                    'render' => 'function() {
                                    if ( type === \'display\' && !data) {
                                        return \'<em>None</em>\';
                                    }
                                    return data;
                    }'
                ]
            ),
            new Column(
                [
                    'data' => 'log_request.session_id',
                    'name' => 'logRequest.session_id',
                    'title' => 'Session ID',
                    'className' => 'desktop',
                    'render' => 'function() {
                                    if ( type === \'display\' ) {
                                        return data.substr(0, 7) +\'&#8230;\';
                                    }
                                    return data;
                                }'
                ]
            )
        ];
    }
}
