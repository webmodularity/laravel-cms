<?php

namespace WebModularity\LaravelCms\DataTables;

use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
{
    public function query()
    {
        //
    }

    protected function placeBootstrapButtons()
    {
        return "\n$('<div class=\"row buttons-container\"><div class=\"col-sm-12\"></div></div>')
                    .prependTo( $('#dataTableBuilder_wrapper') );
                $('#dataTableBuilder').DataTable().buttons().container()
                    .appendTo( $('#dataTableBuilder_wrapper .buttons-container div:eq(1)') );\n";
    }
}