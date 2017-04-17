@include('wmcms::crud.actions.recycle', [
    'id' => isset($recordId) ? $recordId : $id,
    'recordIdent' => isset($recordIdent) ? $recordIdent : $name
])