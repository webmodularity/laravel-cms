<?php

namespace WebModularity\LaravelCms\DataTables;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use WebModularity\LaravelCms\DataTables\Traits\ColumnFilter;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
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
     * DataTable constructor.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @param \Illuminate\Contracts\View\Factory $viewFactory
     */
    public function __construct(Datatables $datatables, Factory $viewFactory)
    {
        parent::__construct($datatables, $viewFactory);

        //$this->datatables
        //    ->eloquent($this->query())
        //    ->addColumn('action', $this->getActionView())
        //    ->rawColumns(['action']);
    }

    public function query()
    {
        //
    }

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

    public static function recycleDataTable($dataTable)
    {
        return $dataTable
            ->onlyTrashed()
            ->addColumn('action', static::$recycleActionView)
            ->rawColumns(['action'])
            ->editColumn('deleted_at', function ($model) {
                return $model->deleted_at ? with(new Carbon($model->deleted_at))->format('m/d/Y h:i:sa') : null;
            });
    }

    /**
     * Modify HTML Builder columns to replace created_at|updated_at columns with deleted_at
     * @param \Yajra\Datatables\Html\Builder $builder
     */
    public static function recycleColumns($builder)
    {
        if ($builder->getColumns().contains('name', 'updated_at')) {
            $builder->removeColumn('updated_at');
        }
        if ($builder->getColumns().contains('name', 'created_at')) {
            $builder->removeColumn('created_at');
        }
        $columns = $builder->getColumns();
        $action = $columns->pop();
        $columns->push(['data' => 'deleted_at', 'title' => 'Deleted At']);
        $columns->push($action);
        $builder->columns($columns->all());
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

    public static function filterUpdatedAt($query, $keyword, $tableName = null)
    {
        static::columnFilterAddQuery(
            $query,
            !is_null($tableName) ? $tableName . '.' . 'updated_at' : 'updated_at',
            static::getColumnFilter($keyword, ['updated_at'])
        );
    }
}
