<?php

namespace WebModularity\LaravelCms\DataTables;

use WebModularity\LaravelCms\DataTables\Traits\ColumnFilter;
use Yajra\Datatables\Services\DataTable;
use DB;

abstract class CmsDataTable extends DataTable
{
    use ColumnFilter;

    public static $actionView;
    public static $order = [[0, 'asc']];
    public static $buttons = ['wmcopy', 'wmcolvis', 'export'];
    public static $exportFilename;
    public static $deleteConfirm = true;
    public static $responsive = false;
    public static $pageLength = 10;
    public static $lengthMenu = [10, 25, 50, 100];
    // Recycle
    public $recycle = false;
    public static $recycleActionView;
    public static $recycleOrder;

    public static $columnFilterDbOperators = ['LIKE', 'NOT LIKE', '=', '!=', '>', '<', '>=', '<='];

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $builder = $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters($this->getBuilderParameters());

        return $this->getActionView()
            ? $builder->addAction(['printable' => false, 'className' => 'all text-center'])
            : $builder;
    }

    protected function getButtons()
    {
        return $this->recycle === true
            ? collect(static::$buttons)->reject(function ($value) {
                return $value == 'create' || $value == 'recycle';
            })->prepend('recycle')->flatten()->all()
            : static::$buttons;
    }

    protected function getActionView()
    {
        return $this->recycle === true ? static::$recycleActionView : static::$actionView;
    }

    protected function getOrder()
    {
        return $this->recycle === true ? static::$recycleOrder : static::$order;
    }

    protected function getDrawCallback()
    {
        if (static::$deleteConfirm === true) {
            if ($this->recycle === true) {
                return static::getRestoreConfirmAlert() . "\n"
                    . static::getPermaDeleteConfirmAlert();
            } else {
                return static::getDeleteConfirmAlert();
            }
        }
    }

    protected function applyScopes($query)
    {
        if ($this->recycle === true) {
            $query->onlyTrashed();
        }

        return parent::applyScopes($query);
    }

    protected function getColumns()
    {
        return [];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        $addRecycled = $this->recycle === true ? '-recycled' : '';
        $uniqueString = $addRecycled . time();
        return !is_null(static::$exportFilename)
            ? static::$exportFilename . $uniqueString
            : (new \ReflectionClass($this))->getShortName() . $uniqueString;
    }

    protected function getBuilderParameters()
    {
        return [
            'buttons' => $this->getButtons(),
            'drawCallback' => "function( settings ) {
                ".$this->getDrawCallback()."
            }",
            'order' => $this->getOrder(),
            'responsive' => (bool) static::$responsive,
            'pageLength' => (int) static::$pageLength,
            'lengthMenu' => (array) static::$lengthMenu
        ];
    }

    public static function getDeleteConfirmAlert()
    {
        return <<< EOT
$('.delete-confirm-button').click(function(){
    var id = $(this).data("id");
    var token = $(this).data("token");
    var recordIdent = $(this).data("record-ident");
    var dtApi = new $.fn.dataTable.Api( settings );
    swal({
        title: 'Delete This Record?',
        text: recordIdent,
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
    function() {
        $.ajax({
            url: location.pathname.replace(/\/+$/, '') + '/' +id,
            method: 'POST',
            data: {
                '_method': 'DELETE',
                '_token': token,
            },
            dataType: 'json'
        })
        .done(function (response, status, xhr) {
            dtApi.ajax.reload(null, false);
            toastr.success(response);
            swal.close();
        })
        .fail(function (xhr, status, error) {
            var errorResponse = xhr.responseText ? JSON.parse(xhr.responseText)
                : 'An unknown server error was encountered when attempting to restore this record.';
            toastr.error(errorResponse);
            swal.close();
        });
    });
});
EOT;
    }

    public static function getRestoreConfirmAlert()
    {
        return <<< EOT
$('.restore-confirm-button').click(function(){
    var id = $(this).data("id");
    var token = $(this).data("token");
    var recordIdent = $(this).data("record-ident");
    var dtApi = new $.fn.dataTable.Api( settings );
    swal({
        title: 'Restore This Record?',
        text: recordIdent,
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Yes, restore it!',
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
    function() {
        $.ajax({
            url: location.pathname.replace(/\/+$/, '') + '/' +id,
            method: 'PUT',
            data: {
                '_token': token,
            },
            dataType: 'json'
        })
        .done(function (response, status, xhr) {
            dtApi.ajax.reload(null, false);
            toastr.success(response);
            swal.close();
        })
        .fail(function (xhr, status, error) {
            var errorResponse = xhr.responseText ? JSON.parse(xhr.responseText)
                : 'An unknown server error was encountered when attempting to restore this record.';
            toastr.error(errorResponse);
            swal.close();
        });
    });
});
EOT;
    }

    public static function getPermaDeleteConfirmAlert()
    {
        return <<< EOT
$('.perma-delete-confirm-button').click(function(){
    var id = $(this).data("id");
    var token = $(this).data("token");
    var recordIdent = $(this).data("record-ident");
    var dtApi = new $.fn.dataTable.Api( settings );
    swal({
        title: 'Permanently Delete This Record?',
        text: recordIdent,
        type: 'error',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Yes, permanently delete it!',
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
    function() {
        $.ajax({
            url: location.pathname.replace(/\/+$/, '') + '/' +id,
            method: 'POST',
            data: {
                '_method': 'DELETE',
                '_token': token,
            },
            dataType: 'json'
        })
        .done(function (response, status, xhr) {
                dtApi.ajax.reload(null, false);
                toastr.success(response);
                swal.close();
        })
        .fail(function (xhr, status, error) {
                var errorResponse = xhr.responseText ? JSON.parse(xhr.responseText)
                    : 'An unknown server error was encountered when attempting to restore this record.';
                toastr.error(errorResponse);
                swal.close();
        });
    });
});
EOT;
    }

    // Shared Filters

    public static function filterId($query, $keyword, $tableName = null)
    {
        static::columnFilterAddQuery(
            $query,
            !is_null($tableName) ? $tableName . '.' . 'id' : 'id',
            static::getColumnFilter($keyword, ['id'])
        );
    }

    public static function filterContact($query, $keyword)
    {
        $columnFilter = static::getColumnFilter($keyword, ['email', 'name']);
        if ($columnFilter->has('column')) {
            if ($columnFilter['column'] == 'email') {
                static::columnFilterAddQuery($query, 'people.email', $columnFilter);
            } elseif ($columnFilter['column'] == 'name') {
                static::columnFilterAddQuery(
                    $query,
                    ['people.first_name', 'people.last_name'],
                    $columnFilter
                );
            }
        } else {
            static::columnFilterAddQuery(
                $query,
                ['people.email', 'people.first_name', 'people.last_name'],
                $columnFilter
            );
        }
    }

    public static function filterCreatedAt($query, $keyword, $tableName = null)
    {
        static::columnFilterAddQuery(
            $query,
            !is_null($tableName) ? $tableName . '.' . 'created_at' : 'created_at',
            static::getColumnFilter($keyword, ['created_at'])
        );
    }

    public static function filterUpdatedAt($query, $keyword, $tableName = null)
    {
        static::columnFilterAddQuery(
            $query,
            !is_null($tableName) ? $tableName . '.' . 'updated_at' : 'updated_at',
            static::getColumnFilter($keyword, ['updated_at'])
        );
    }

    public static function filterDeletedAt($query, $keyword, $tableName = null)
    {
        static::columnFilterAddQuery(
            $query,
            !is_null($tableName) ? $tableName . '.' . 'deleted_at' : 'deleted_at',
            static::getColumnFilter($keyword, ['deleted_at'])
        );
    }

    public static function filterPhonePerson($query, $keyword)
    {
        static::columnFilterAddQuery(
            $query,
            [
                'phones.area_code',
                'phones.number',
                DB::raw("CONCAT('(', area_code, ')', SUBSTR(number, 1, 3), '-', SUBSTR(number, 4, 4))"),
                DB::raw("CONCAT(SUBSTR(number, 1, 3), '-', SUBSTR(number, 4, 4))")
            ],
            static::getColumnFilter($keyword, ['phone']),
            [
                'table' => DB::raw('phones LEFT JOIN person_phone ON phones.id = person_phone.phone_id'),
                'where' => DB::raw('people.id = person_phone.person_id')
            ]
        );
    }

    public static function filterAddress($query, $keyword)
    {
        static::columnFilterAddQuery(
            $query,
            [
                'addresses.city',
                'addresses.zip',
                'address_states.name',
                'address_states.iso'
            ],
            static::getColumnFilter($keyword, ['address'])
        );
    }

    public static function filterFullName($query, $keyword)
    {
        static::columnFilterAddQuery(
            $query,
            [
                'first_name',
                'middle_name',
                'last_name'
            ],
            static::getColumnFilter($keyword, ['name'])
        );
    }
}
