<?php

namespace WebModularity\LaravelCms\DataTables;

use WebModularity\LaravelUser\User;
use Carbon\Carbon;
use Yajra\Datatables\Html\Column;
use Auth;
use DB;

class UserDataTable extends CmsDataTable
{
    public static $actionView = 'wmcms::users.actions.index';
    public static $order = [[1, "asc"]];
    public static $buttons = ['create', 'wmcopy', 'wmcolvis', 'export', 'recycle'];
    public static $exportFilename = 'user';
    // Recycle
    public static $recycleActionView = 'wmcms::users.actions.recycle';
    public static $recycleOrder = [[6, "desc"]];

    /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function dataTable()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', $this->getActionView())
            ->editColumn('updated_at', function (User $user) {
                return with(new Carbon($user->person->updated_at))->format('m/d/Y h:i:sa');
            })
            ->editColumn('deleted_at', function (User $user) {
                return $user->person->deleted_at
                    ? with(new Carbon($user->person->deleted_at))->format('m/d/Y h:i:sa')
                    : null;
            })
            ->filterColumn('id', function ($query, $keyword) {
                return static::filterId($query, $keyword, 'users');
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                return static::filterUpdatedAt($query, $keyword, 'users');
            })
            ->filterColumn('deleted_at', function ($query, $keyword) {
                return static::filterDeletedAt($query, $keyword, 'users');
            })
            ->addColumn('full_name', function (User $user) {
                return view('wmcms::partials.name-full')->with('person', $user->person);
            })
            ->filterColumn('full_name', function ($query, $keyword) {
                static::filterFullName($query, $keyword);
            })
            ->orderColumn('full_name', 'last_name $1, first_name $1, middle_name $1')
            ->addColumn('phones', function (User $user) {
                if (is_null($user->person->phones) || $user->person->phones->count() < 1) {
                    return null;
                }
                return $user->person->phones->sortBy('pivot.phone_type_id')->map(function ($phone) {
                    return view('wmcms::partials.phone-icon')->with('phone', $phone)->render();
                })->implode('<br />');
            })
            ->filterColumn('phones', function ($query, $keyword) {
                static::filterPhonePerson($query, $keyword);
            })
            ->addColumn('address_primary', function (User $user) {
                if (is_null($user->person->addresses) || $user->person->addresses->count() < 1) {
                    return null;
                }

                return view('wmcms::partials.address', ['address' => $user->person->addresses->first()->toArray()]);
            })
            ->filterColumn('address_primary', function ($query, $keyword) {
                static::filterPhonePerson($query, $keyword);
            })
            ->orderColumn('address_primary', 'city $1, iso $1, zip, $1, street $1')
            ->addColumn('user_role', function (User $user) {
                return studly_case($user->role->slug);
            })
            ->filterColumn('user_role', function ($query, $keyword) {
                static::columnFilterAddQuery(
                    $query,
                    [
                        'user_roles.slug',
                        DB::raw("REPLACE(user_roles.slug, '-', '')"),
                    ],
                    static::getColumnFilter($keyword, ['email'])
                );
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
            $this->recycle ? 'deleted_at' : 'updated_at'
        ];
    }
}
