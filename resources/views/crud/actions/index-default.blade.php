@include('wmcms::crud.actions.index', [
    'editUrl' => route(Route::current()->uri() . '.edit', ['id' => $id]),
    'ident' => $name or $slug
])