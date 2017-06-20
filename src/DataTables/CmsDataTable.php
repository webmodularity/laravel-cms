<?php

namespace WebModularity\LaravelCms\DataTables;

use DB;
use Yajra\Datatables\Services\DataTable;

class CmsDataTable extends DataTable
{
    protected $actionView;

    public static $columnFilterDbOperators = ['LIKE', 'NOT LIKE', '=', '!=', '>', '<', '>=', '<='];

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
            dataType: 'json'
        })
        .done(function (response, status, xhr) {
            dtApi.ajax.reload(null, false);
            toastr.success(response);
            swal.close();
        })
        .fail(function (xhr, status, error) {
            var errorResponse = xhr.responseText ? JSON.parse(xhr.responseText)
                : 'An unknown server error was encountered when attempting to restore this record.';
            toastr.error(errorResponse);
            swal.close();
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
            dataType: 'json'
        })
        .done(function (response, status, xhr) {
            dtApi.ajax.reload(null, false);
            toastr.success(response);
            swal.close();
        })
        .fail(function (xhr, status, error) {
            var errorResponse = xhr.responseText ? JSON.parse(xhr.responseText)
                : 'An unknown server error was encountered when attempting to restore this record.';
            toastr.error(errorResponse);
            swal.close();
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
            dataType: 'json'
        })
        .done(function (response, status, xhr) {
                dtApi.ajax.reload(null, false);
                toastr.success(response);
                swal.close();
        })
        .fail(function (xhr, status, error) {
                var errorResponse = xhr.responseText ? JSON.parse(xhr.responseText)
                    : 'An unknown server error was encountered when attempting to restore this record.';
                toastr.error(errorResponse);
                swal.close();
        });
    });
});
EOT;
    }

    // Column Filter

    /**
     * Create a columnFilter collection based on passed keyword
     * Format: [column_name]:(=|!=|!|>|<|>=|<=)?[keyword]
     * If keyword does not contain a : it will be used as the keyword and all columns assumed
     * @param string $keyword
     * @return \Illuminate\Support\Collection
     */
    public static function getColumnFilter($keyword)
    {
        if (strpos($keyword, ':') !== false
            && preg_match('/^([a-zA-Z_]+):(=|!=|!|>|<|>=|<=)?([^<>=!]+)$/', $keyword, $keywordMatch)) {
            return collect([
                'column' => $keywordMatch[1],
                'operator' => static::columnFilterGetDbOperator($keywordMatch[2]),
                'keyword' => static::columnFilterFormatKeyword(
                    $keywordMatch[3],
                    static::columnFilterGetDbOperator($keywordMatch[2])
                )
            ]);
        }

        return collect([
            'operator' => static::columnFilterGetDbOperator(null),
            'keyword' => static::columnFilterFormatKeyword(
                $keyword,
                static::columnFilterGetDbOperator(null)
            )
        ]);
    }

    public static function columnFilterFormatKeyword($keyword, $operator)
    {
        return strtolower($operator) == 'like' || strtolower($operator) == 'not like'
            ? "%$keyword%"
            : $keyword;
    }

    public static function columnFilterGetDbOperator($inputOperator)
    {
        if ($inputOperator == '!') {
            return 'NOT LIKE';
        } elseif (!empty($inputOperator) && in_array($inputOperator, static::$columnFilterDbOperators)) {
            return $inputOperator;
        }
        return 'LIKE';
    }

    // Shared Filters

    public static function filterContact($query, $keyword)
    {
        $columnFilter = static::getColumnFilter($keyword);
        if ($columnFilter->has('column')) {
            if ($columnFilter['column'] == 'email') {
                $query->where(
                    '`people`.`email`',
                    $columnFilter['operator'],
                    $columnFilter['keyword']
                );
            } elseif ($columnFilter['column'] == 'name') {
                $query->where(
                    DB::raw("CONCAT_WS(',', `people`.`last_name`, '`people`.`first_name`')"),
                    $columnFilter['operator'],
                    $columnFilter['keyword']
                );
            }
        } else {
            $filterColumns =                 [
                'people.email',
                'people.first_name',
                'people.middle_name',
                'people.last_name'
            ];
            foreach ($filterColumns as $filterColumn) {
                $query->orWhere(
                    $filterColumn,
                    $columnFilter['operator'],
                    $columnFilter['keyword']
                );
            }
        }
    }
}
