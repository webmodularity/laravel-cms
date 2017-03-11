<?php

namespace WebModularity\LaravelCms\DataTables;

use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
{
    public function query()
    {
        //
    }

    public static function extendDefaultsScript()
    {
        return <<< EOT
<script>
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            search: "<div class="has-feedback">_INPUT_<span class="glyphicon glyphicon-search form-control-feedback"></span></div>",
            searchPlaceholder: "Search...",
            lengthMenu: "Results per page: _MENU_"
        },
        dom: "<'row'<'col-sm-9'B><'col-sm-3'<'pull-right'l>f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });
</script>
EOT;

    }

    protected function placeBootstrapButtons()
    {
        return "\n$('<div class=\"row buttons-container\"><div class=\"col-sm-12\"></div></div>')
                    .prependTo( $('#dataTableBuilder_wrapper') );
                $('#dataTableBuilder').DataTable().buttons().container()
                    .appendTo( $('#dataTableBuilder_wrapper div:eq(1)') );\n";
    }

    protected function getDeleteConfirmAlert()
    {
        return "$('.delete-confirm-button').click(function(){
    var id = $(this).data(\"id\");
    var token = $(this).data(\"token\");
    var recordIdent = $(this).data('record-ident');
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
});";
    }
}