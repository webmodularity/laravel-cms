@include('wmms::crud.actions.index', [
    'editUrl' => route(Route::getCurrentRoute()->uri . '.edit', ['id' => $id]),
    'ident' => $name or $slug
])