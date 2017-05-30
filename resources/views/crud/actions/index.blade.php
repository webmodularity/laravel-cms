<div style="white-space: nowrap">
    <a href="{{ route(preg_replace("/\.".Route::current()->getActionMethod()."$/", '.edit', Route::current()->getName()), ['id' => $id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
    <button type="button" class="btn btn-xs btn-danger delete-confirm-button" data-id="{{ $id }}" data-token="{{ csrf_token() }}" data-record-ident="{{ $recordIdent }}"><i class="fa fa-trash-o"></i></button>
</div>