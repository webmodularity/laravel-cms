<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">

        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <!-- /.box-header -->
    <div class="box-body">
        {!! $dataTable->table(['class' => "table table-hover table-bordered"]) !!}
        <div class="row">
            @yield('box-tools')
        </div>
    </div>
    <!-- /.box-body -->
</div>

@push('js')
<script>
    $(function () {
        var resultsPerPageSelect = $('#dataTableBuilder_length').find('select');
        resultsPerPageSelect.selectpicker({
            style: 'btn-default btn-sm',
            width: 'fit',
            dropdownAlignRight: true
        });
    });
</script>
@endpush