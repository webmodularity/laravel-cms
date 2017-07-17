<?php
$relatedTableId = isset($relatedTableId) && !empty($relatedTableId)
    ? $relatedTableId
    : 'relatedTable';
?>

@push('js')
<script>
    $(function () {
        WMCMS.DT.TABLES[{{ $relatedTableId }}] = $('#{{ $relatedTableId }}').DataTable({
            data: [
                @yield($relatedTableId . 'Data')
            ],
            columns: [
                @yield($relatedTableId . 'Columns')
            ],
            columnDefs: [
                @yield($relatedTableId . 'ColumnDefs')
            ]
        });
    });
</script>
@endpush

@include('wmcms::crud.datatable-box-mini', [
    'tableId' => $relatedTableId
])