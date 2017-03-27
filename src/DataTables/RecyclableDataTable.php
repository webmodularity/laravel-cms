<?php

namespace WebModularity\LaravelCms\DataTables;

trait RecyclableDataTable
{

    protected function recycleDataTable($dataTable)
    {
        return $dataTable->onlyTrashed();
    }

}
