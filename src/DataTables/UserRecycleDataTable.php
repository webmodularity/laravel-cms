<?php

namespace WebModularity\LaravelCms\DataTables;

class UserRecycleDataTable extends UserDataTable
{
    use RecyclableDataTable;

    protected $actionView = 'users.actions.recycle';

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
