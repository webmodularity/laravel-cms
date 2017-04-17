<div style="white-space: nowrap">
    <a href="{{ $editUrl  }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>&nbsp;Edit</a>
    <button type="button" class="btn btn-xs btn-danger delete-confirm-button" data-id="{{ $id }}" data-token="{{ csrf_token() }}" data-record-ident="{{ $ident }}"><i class="fa fa-trash-o"></i>&nbsp;Delete</button>
</div>