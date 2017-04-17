@include('wmms::crud.actions.index', [
    'editUrl' => route(Route::currentRouteName() . '.edit', ['id' => $id]),
    'ident' => $name or $slug
])