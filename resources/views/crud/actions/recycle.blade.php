<div style="white-space: nowrap">
    <button type="button" class="btn btn-xs btn-success restore-confirm-button" data-id="{{ $id }}" data-token="{{ csrf_token() }}" data-record-ident="{{ $recordIdent }}"><i class="fa fa-undo"></i></button>
    <button type="button" class="btn btn-xs btn-danger perma-delete-confirm-button" data-id="{{ $id }}" data-token="{{ csrf_token() }}" data-record-ident="{{ $recordIdent }}"><i class="fa fa-trash"></i></button>
</div>