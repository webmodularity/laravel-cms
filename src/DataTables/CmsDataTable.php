<?php

namespace WebModularity\LaravelCms\DataTables;

use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
{
    protected $actionView;

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
            ? $builder->addAction(['width' => '80px', 'printable' => false])
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
            success: function (response) {
                swal({
                    title: 'Successfully Deleted Record',
                    text: response.success,
                    type: 'success',
                    confirmButtonClass: 'btn-primary',
                },
                function() {
                    dtApi.ajax.reload(null, false);
                });
            },
            error: function () {
                swal({
                    title: 'Delete Failed!',
                    text: response.errors.error
                        || 'An unknown server error was encountered when attempting to delete this record.',
                    type: 'error',
                    confirmButtonClass: 'btn-primary',
                });
            }
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
            success: function (response) {
                swal({
                    title: 'Successfully Restored Record',
                    text: response.success,
                    type: 'success',
                    confirmButtonClass: 'btn-primary',
                },
                function() {
                    dtApi.ajax.reload(null, false);
                });
            },
            error: function () {
                swal({
                    title: 'Restore Failed!',
                    text: 'An unknown server error was encountered when attempting to restore this record.',
                    type: 'error',
                    confirmButtonClass: 'btn-primary',
                });
            }
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
            success: function (response) {
                swal({
                    title: 'Permanently Deleted Record',
                    text: response.success,
                    type: 'success',
                    confirmButtonClass: 'btn-primary',
                },
                function() {
                    dtApi.ajax.reload(null, false);
                });
            },
            error: function () {
                swal({
                    title: 'Permanent Delete Failed!',
                    text: 'An unknown server error was encountered when attempting to delete this record.',
                    type: 'error',
                    confirmButtonClass: 'btn-primary',
                });
            }
        });
    });
});
EOT;
    }
}
