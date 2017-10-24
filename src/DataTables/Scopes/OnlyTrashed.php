<?php

namespace LaravelCms\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class OnlyTrashed implements DataTableScope
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->onlyTrashed();
    }
}
