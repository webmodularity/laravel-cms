<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            @yield('box-tools')
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {!! $dataTable->table(['class' => "table table-hover table-bordered"]) !!}
    </div>
    <!-- /.box-body -->
</div>

@push('js')
    @include('wmcms::crud.datatable-common')
    {!! $dataTable->scripts() !!}
@endpush