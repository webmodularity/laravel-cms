<?php

namespace WebModularity\LaravelCms\DataTables;

use DB;
use WebModularity\LaravelCms\DataTables\Traits\ColumnFilter;
use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
{
    use ColumnFilter;

    protected $actionView;

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
        return ['wmcopy', 'wmcolvis', 'export'];
    }

    protected function getDrawCallback()
    {
        //
    }

    protected function getColumns()
    {
        return [];
    }

    protected function getOrder()
    {
        return [[0, 'asc']];
    }

    protected function getBuilderParameters()
    {
        return [
            'buttons' => $this->getButtons(),
            'drawCallback' => "function( settings ) {
                ".$this->getDrawCallback()."
            }",
            'order' => $this->getOrder()
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
        $columnName = !is_null($tableName) ? $tableName . '.' . 'id' : 'id';
        $columnFilter = static::getColumnFilter($keyword, ['id']);
        static::columnFilterAddQuery($query, $columnName, $columnFilter);
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
                    DB::raw("CONCAT_WS(' ', people.first_name, people.last_name)"),
                    $columnFilter
                );
            }
        } else {
            static::columnFilterAddQuery(
                $query,
                [
                    'people.email',
                    'people.first_name',
                    'people.middle_name',
                    'people.last_name'
                ],
                $columnFilter
            );
        }
    }
}
