<?php

namespace WebModularity\LaravelCms\DataTables;

use Carbon\Carbon;
use WebModularity\LaravelUser\LogUser;
use Yajra\Datatables\Html\Column;

class LogUserDataTable extends CmsDataTable
{
    protected $actionView = 'wmcms::crud.actions.details';

    /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function dataTable()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', $this->actionView)
            ->addColumn('ip_address', function (LogUser $logUser) {
                return isset($logUser->logRequest->ipAddress) && !empty($logUser->logRequest->ipAddress->ip)
                    ? $logUser->logRequest->ipAddress->ip
                    : null;
            })
            ->filterColumn('ip_address', function ($query, $keyword) {
                $query->whereRaw("INET6_NTOA(ip) like ?", ["%$keyword%"]);
            })
            ->orderColumn('ip_address', 'ip $1')
            ->editColumn('created_at', function (LogUser $logUser) {
                return $logUser->created_at ? with(new Carbon($logUser->created_at))->format('m/d/Y h:i:sa') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(`log_users`.created_at,'%m/%d/%Y %h:%i:%s%p') like ?", ["%$keyword%"]);
            })
            ->orderColumn('created_at', '`log_users`.created_at $1')
            ->addColumn('social_provider_name', function (LogUser $logUser) {
                return !is_null($logUser->socialProvider) ? $logUser->socialProvider->getName() : null;
            })
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
            ->leftJoin('log_requests', 'log_users.log_request_id', '=', 'log_requests.id')
            ->leftJoin('log_ip_addresses', 'log_requests.ip_address_id', '=', 'log_ip_addresses.id')
            ->leftJoin('log_url_paths', 'log_requests.url_path_id', '=', 'log_url_paths.id')
            ->leftJoin('log_request_methods', 'log_requests.request_method_id', '=', 'log_request_methods.id')
            ->leftJoin('log_user_actions', 'log_users.user_action_id', '=', 'log_user_actions.id')
            ->with(
                [
                    'user.person',
                    'socialProvider'
                ]
            )
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
                    'data' => 'log_user_actions.slug',
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
                    'name' => 'socialProvider.slug',
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

    protected function getBuilderParameters()
    {
        return array_merge(
            parent::getBuilderParameters(),
            [
                'responsive' => true,
                'pageLength' => 50
            ]
        );
    }

    protected function getOrder()
    {
        return [[0, "desc"]];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'userlogdatatable_' . time();
    }
}
