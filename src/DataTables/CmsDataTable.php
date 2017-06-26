<?php

namespace WebModularity\LaravelCms\DataTables;

use Carbon\Carbon;
use WebModularity\LaravelCms\DataTables\Traits\ColumnFilter;
use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
{
    use ColumnFilter;

    protected $actionView;
    protected $order = [[0, 'asc']];
    protected $buttons = ['wmcopy', 'wmcolvis', 'export'];
    protected $filename;
    protected $deleteConfirm = true;
    protected $responsive = false;
    protected $pageLength = 10;
    protected $lengthMenu = [10, 25, 50, 100];
    // Recycle
    public $recycle = false;
    protected $recycleActionView;
    protected $recycleOrder;

    public static $columnFilterDbOperators = ['LIKE', 'NOT LIKE', '=', '!=', '>', '<', '>=', '<='];

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

        return $this->actionView
            ? $builder->addAction(['printable' => false, 'className' => 'all text-center'])
            : $builder;
    }

    protected function getButtons()
    {
        return $this->recycle === true
            ? collect($this->buttons)->reject(function ($value) {
                return $value == 'create' || $value == 'recycle';
            })->prepend('recycle')->flatten()->all()
            : $this->buttons;
    }

    protected function getActionView()
    {
        return $this->recycle === true ? $this->recycleActionView : $this->actionView;
    }

    protected function getOrder()
    {
        return $this->recycle === true ? $this->recycleOrder : $this->order;
    }

    protected function getDrawCallback()
    {
        if ($this->deleteConfirm === true) {
            if ($this->recycle === true) {
                return $this->getRestoreConfirmAlert() . "\n"
                    . $this->getPermaDeleteConfirmAlert();
            } else {
                return $this->getDeleteConfirmAlert();
            }
        }
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
        return !is_null($this->filename)
            ? $this->filename . time()
            : (new \ReflectionClass($this))->getShortName() . time();
    }

    public static function recycleDataTable($dataTable)
    {
        return $dataTable
            ->onlyTrashed()
            ->editColumn('deleted_at', function ($model) {
                return $model->deleted_at ? with(new Carbon($model->deleted_at))->format('m/d/Y h:i:sa') : null;
            });
    }

    public static function recycleColumns($builder)
    {
        $columns = $builder->getColumns();
        if ($columns.contains('name', 'updated_at')) {
            $builder->removeColumn('updated_at');
        }
        if ($columns.contains('name', 'created_at')) {
            $builder->removeColumn('created_at');
        }
        $builder->addCOlumn([
            'name' => 'deleted_at'
        ]);
    }

    protected function getBuilderParameters()
    {
        return [
            'buttons' => $this->getButtons(),
            'drawCallback' => "function( settings ) {
                ".$this->getDrawCallback()."
            }",
            'order' => $this->getOrder(),
            'responsive' => (bool) $this->responsive,
            'pageLength' => (int) $this->pageLength,
            'lengthMenu' => (array) $this->lengthMenu
        ];
    }

    protected function getDeleteConfirmAlert()
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

    protected function getRestoreConfirmAlert()
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

    protected function getPermaDeleteConfirmAlert()
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
