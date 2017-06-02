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
            success: function (response) {
                dtApi.ajax.reload(null, false);
                toastr.success(response);
                swal.close();
            },
            error: function (xhr, status, error) {
                toastr.error(JSON.parse(xhr.responseText) || 'An unknown server error was encountered when attempting to delete this record.');
                swal.close();
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
                console.log('success response');
                dtApi.ajax.reload(null, false);
                toastr.success(response);
                swal.close();
            },
            error: function (xhr, status, error) {
                console.log('error response');
                var errorResponse = JSON.parse(xhr.responseText) || 'An unknown server error was encountered when attempting to restore this record.';
                toastr.error(errorResponse);
                swal.close();
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
        })
        .done(function (response, status, xhr) {
                console.log(xhr.responseText);
                dtApi.ajax.reload(null, false);
                toastr.success(response);
                swal.close();
        })
        .fail(function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                var errorResponse = 'An unknown server error was encountered when attempting to restore this record.';
                toastr.error(errorResponse);
                swal.close();
        });
    });
});
EOT;
    }
}
