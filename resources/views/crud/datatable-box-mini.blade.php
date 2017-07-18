<?php
$tableId = isset($tableId) && !empty($tableId)
    ? $tableId
    : 'dataTableMini';
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            <div class="form-group form-group-sm has-feedback pull-right" style="margin-left: 5px;">
                <input id="{{ $tableId }}Search" class="form-control input-sm" placeholder="Quick Search..." type="text">
                <span class="fa fa-search form-control-feedback"></span>
            </div>
            @yield('box-tools')
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <!-- /.box-header -->
    <div class="box-body">
        <table id="{{ $tableId }}" class="table table-hover table-bordered"></table>
    </div>
    <!-- /.box-body -->
</div>

@push('js')
<script>
    $.extend(true, $.fn.dataTable.defaults, {
        dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        order: {!! $defaultOrder or '[[0, "asc"]]' !!}
    });

    $(function () {
        $.fn.dataTable.moment('m/d/Y h:i:sa');
        $('#{{ $tableId }}Search').keyup(function() {
            WMCMS.DT.TABLES['{{ $tableId }}'].search($(this).val()).draw();
        });
    });
</script>
@endpush