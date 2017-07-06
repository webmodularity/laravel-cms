@include('wmcms::crud.datatable-filter', [
    'daterangepicker' => Route::getCurrentRoute()->getActionMethod() == 'recycle' ? 'deleted_at' : 'updated_at',
     'columnFilters' => [
        'HEADER:Columns',
        'ID'
     ]
])