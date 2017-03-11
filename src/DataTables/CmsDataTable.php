<?php

namespace WebModularity\LaravelCms\DataTables;

use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
{
    public function query()
    {
        //
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
            success: function () {
                swal({
                    title: 'Successfully Deleted Record',
                    text: recordIdent,
                    type: 'success',
                    timer: 3000,
                    confirmButtonClass: 'btn-primary',
                });
                dtApi.ajax.reload(null, false);
            },
            error: function () {
                swal({
                    title: 'Delete Failed!',
                    text: 'An unknown server error was encountered when attempting to delete this record. ',
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
