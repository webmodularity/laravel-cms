<div style="white-space: nowrap">
    <button type="button" class="btn btn-xs btn-success restore-confirm-button" data-id="{{ $id }}" data-token="{{ csrf_token() }}" data-record-ident="{{ $name }}"><i class="fa fa-undo"></i>&nbsp;Restore</button>
    <button type="button" class="btn btn-xs btn-danger perma-delete-confirm-button" data-id="{{ $id }}" data-token="{{ csrf_token() }}" data-record-ident="{{ $name }}"><i class="fa fa-trash"></i>&nbsp;Delete</button>
</div>