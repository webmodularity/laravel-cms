<?php

namespace WebModularity\LaravelCms\DataTables;

use Carbon\Carbon;

trait RecyclableDataTable
{

    protected function recycleDataTable($dataTable)
    {
        return $dataTable
            ->onlyTrashed()
            ->editColumn('deleted_at', function ($model) {
                return $model->deleted_at ? with(new Carbon($model->deleted_at))->format('m/d/Y h:i:sa') : null;
            })
            ->filterColumn('deleted_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(deleted_at,'%m/%d/%Y %h:%i:%s%p') like ?", ["%$keyword%"]);
            });
    }

    protected function recycleQuery($query)
    {
        return $query
            ->onlyTrashed();
    }
}
