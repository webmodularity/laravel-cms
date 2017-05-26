<?php

namespace WebModularity\LaravelCms\DataTables;

use WebModularity\LaravelUser\User;
use Carbon\Carbon;
use Yajra\Datatables\Html\Column;
use Auth;

class UserDataTable extends CmsDataTable
{
    protected $actionView = 'wmcms::users.actions.index';

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
            ->addColumn('full_name', function (User $user) {
                return view('wmcms::partials.name-full')->with('person', $user->person);
            })
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereRaw(
                    "first_name like ?
                        OR middle_name like ?
                        OR last_name like ?
                        OR CONCAT(first_name, ' ', last_name) like ?
                        OR CONCAT(first_name, ' ', middle_name, ' ', last_name) like ?",
                    ["$keyword%", "$keyword%", "$keyword%", "$keyword%", "$keyword%"]
                );
            })
            ->orderColumn('full_name', 'last_name $1, first_name $1, middle_name $1')

            ->editColumn('updated_at', function (User $user) {
                return with(new Carbon($user->updated_at))->format('m/d/Y h:i:sa');
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(users.updated_at,'%m/%d/%Y %h:%i:%s%p') like ?", ["%$keyword%"]);
            })
            ->addColumn('user_role', function (User $user) {
                return studly_case($user->role->slug);
            })
            ->filterColumn('user_role', function ($query, $keyword) {
                $query->orWhereRaw("REPLACE(user_roles.slug, '-', '') like ?", ["%$keyword%"]);
            })
            ->orderColumn('user_role', 'user_roles.slug $1')
            ->rawColumns(['phones', 'address_primary', 'action']);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = User::query()
            ->select('users.*')
            ->visibleByRole(Auth::user()->role_id)
            ->leftJoin('user_roles', 'user_roles.id', '=', 'users.role_id')
            ->leftJoin('people', 'people.id', '=', 'users.person_id')
            ->leftJoin('address_person', 'people.id', '=', 'address_person.person_id')
            ->leftJoin('addresses', 'address_person.address_id', '=', 'addresses.id')
            ->leftJoin('address_states', 'addresses.state_id', '=', 'address_states.id')
            ->with(['role', 'person.addresses', 'person.phones']);

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
                    'data' => 'id',
                    'title' => 'ID'
                ]
            ),
            new Column(
                [
                    'data' => 'person.email',
                    'title' => 'Email'
                ]
            ),
            new Column(
                [
                    'data' => 'user_role',
                    'title' => 'Role'
                ]
            ),
            new Column(
                [
                    'data' => 'full_name',
                    'title' => 'Name'
                ]
            ),
            new Column(
                [
                    'data' => 'phones',
                    'title' => 'Phones',
                    'orderable' => false
                ]
            ),
            new Column(
                [
                    'data' => 'address_primary',
                    'title' => 'Address'
                ]
            ),
            'updated_at'
        ];
    }

    protected function getButtons()
    {
        return ['create', 'wmcopy', 'wmcolvis', 'export', 'recycle'];
    }

    protected function getDrawCallback()
    {
        return $this->getDeleteConfirmAlert();
    }

    protected function getOrder()
    {
        return [[1, "asc"]];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'user_' . time();
    }
}
