@include('wmcms::crud.actions.index', [
    'editUrl' => route(Route::current()->uri() . '.edit', ['id' => $id]),
    'recordIdent' => isset($recordIdent) ? $recordIdent : $name
])