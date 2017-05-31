<?php

namespace WebModularity\LaravelCms\DataTables;

use Carbon\Carbon;

class UserRecycleDataTable extends UserDataTable
{
    use RecyclableDataTable;

    protected $actionView = 'wmcms::users.actions.recycle';

    protected function recycleDataTable($dataTable)
    {
        return $dataTable
            ->onlyTrashed()
            ->editColumn('deleted_at', function ($model) {
                return $model->deleted_at ? with(new Carbon($model->deleted_at))->format('m/d/Y h:i:sa') : null;
            })
            ->filterColumn('deleted_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(users.deleted_at,'%m/%d/%Y %h:%i:%s%p') like ?", ["%$keyword%"]);
            });
    }

    protected function getOrder()
    {
        return [[6, "desc"]];
    }

    public function dataTable()
    {
        return $this->recycleDataTable(parent::dataTable());
    }

    public function query()
    {
        return $this->recycleQuery(parent::query());
    }

    protected function getColumns()
    {
        return $this->recycleGetColumns(parent::getColumns());
    }

    protected function getButtons()
    {
        return $this->recycleGetButtons();
    }

    protected function getDrawCallback()
    {
        return $this->recycleGetDrawCallback();
    }
}
