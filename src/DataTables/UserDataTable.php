<?php

namespace WebModularity\LaravelCms\DataTables;

use WebModularity\LaravelUser\User;
use Carbon\Carbon;
use Yajra\Datatables\Html\Column;

class UserDataTable extends CmsDataTable
{
    protected $actionView = 'wmcms::user.actions.index';

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
            ->addColumn('phones', function (User $user) {
                if (is_null($user->person->phones) || $user->person->phones->count() < 1) {
                    return null;
                }
                return $user->person->phones->sortBy('pivot.phone_type_id')->map(function ($phone) {
                    return view('wmcms::partials.phone-icon')->with('phone', $phone)->render();
                })->implode('<br />');
            })
            ->filterColumn('phones', function ($query, $keyword) {
                $query->orWhereRaw(
                    "(
                    select count(1) from `phones` inner join `person_phone` 
                    ON `phones`.`id` = `person_phone`.`phone_id` where `people`.`id` = `person_phone`.`person_id`
                        AND (
                        CONCAT('(', area_code, ')', SUBSTR(number, 1, 3), '-', SUBSTR(number, 4, 4)) like ?
                        OR CONCAT(SUBSTR(number, 1, 3), '-', SUBSTR(number, 4, 4)) = ?
                        OR number like ?
                        OR CONCAT('x', extension) like ?
                        )
                    ) >= 1",
                    ["$keyword%", "$keyword", "%$keyword%", "%$keyword%", "$keyword%"]
                );
            })
            ->addColumn('address_primary', function (User $user) {
                if (is_null($user->person->addresses) || $user->person->addresses->count() < 1) {
                    return null;
                }

                return view('wmcms::partials.address', ['address' => $user->person->addresses->first()->toArray()]);
            })
            ->filterColumn('address_primary', function ($query, $keyword) {
                $query->orWhereRaw(
                    "(street like ? 
                        OR city like ? 
                        OR address_states.name like ? 
                        OR zip like ? 
                        OR CONCAT(city, ', ', iso) like ?)",
                    ["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "$keyword%"]
                );
            })
            ->orderColumn('address_primary', 'city $1, iso $1, zip, $1, street $1')
            ->editColumn('updated_at', function (User $user) {
                return with(new Carbon($user->updated_at))->format('m/d/Y h:i:sa');
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(users.updated_at,'%m/%d/%Y %h:%i:%s%p') like ?", ["%$keyword%"]);
            })
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
            ->leftJoin('people', 'people.id', '=', 'users.person_id')
            ->leftJoin('address_person', 'people.id', '=', 'address_person.person_id')
            ->leftJoin('addresses', 'address_person.address_id', '=', 'addresses.id')
            ->leftJoin('address_states', 'addresses.state_id', '=', 'address_states.id')
            ->with(['person.addresses', 'person.phones']);

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
            'person.email',
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
        return [[2, "asc"]];
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
