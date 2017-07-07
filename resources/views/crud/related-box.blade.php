<?php
$relatedTableId = isset($relatedTableId) && !empty($relatedTableId)
    ? $relatedTableId
    : 'relatedTable';
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle or 'Related' !!}</h3>
        <div class="box-tools pull-right">
            @yield('box-tools')
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="{{ $relatedTableId }}" class="table table-hover table-bordered"></table>
    </div>
    <!-- /.box-body -->
</div>

@push('js')
<script>
    $(function () {
        $.fn.dataTable.moment('m/d/Y h:i:sa');
        $('#{{ $relatedTableId }}').DataTable({
            data: [
                @yield($relatedTableId . 'Data')
            ],
            columns: [
                @yield($relatedTableId . 'Columns')
            ],
            columnDefs: [
                @yield($relatedTableId . 'ColumnDefs')
            ],
            "paging": true,
            "lengthChange": false,
            "dom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'pf>>",
            "language": {
                "search": "<div class='has-feedback'>_INPUT_<span class='glyphicon glyphicon-search form-control-feedback'></span></div>",
                "searchPlaceholder": "Search..."
            },
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": {!! $defaultOrder or '[[0, "asc"]]' !!}
        });
    });
    $('#{{ $relatedTableId }}_filter').appendTo($('#{{ $relatedTableId }}').closest('div.box').find('div.box-header div.box-tools'));
    console.log($('#{{ $relatedTableId }}_filter'));
</script>
@endpush