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

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    protected function recycleQuery($query)
    {
        return $query
            ->onlyTrashed();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function recycleGetColumns($columns)
    {
        $filtered = array_where($columns, function ($value, $key) {
            return !is_string($value) || ($value != 'updated_at' && $value != 'created_at');
        });

        return array_push($filtered, 'deleted_at');
    }
}
