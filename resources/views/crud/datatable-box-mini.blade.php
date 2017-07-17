<?php
$tableId = isset($tableId) && !empty($tableId)
    ? $tableId
    : 'dataTableMini';
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            <div class="input-group input-group-sm" style="width: 150px;">
                <input class="form-control pull-right" placeholder="Search..." type="text">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
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
</script>
@endpush