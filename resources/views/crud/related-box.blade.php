<?php
$relatedTableId = isset($relatedTableId) && !empty($relatedTableId)
    ? $relatedTableId
    : 'related-table';
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
        <table id="{{ $relatedTableId }}" class="table table-hover table-bordered">
            <thead>
                @yield($relatedTableId . '-header')
            </thead>
            <tbody>
                @yield($relatedTableId . '-rows')
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

@push('js')
@dtdefaults('{{ $relatedTableId }}')
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
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": {!! $defaultOrder or '[[0, "asc"]]' !!}
        });
    });
</script>
@endpush