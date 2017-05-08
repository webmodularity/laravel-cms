@extends('wmcms::crud.edit-sm6')

@section('content')
<div class="row">
    <div class="col-sm-6">

    </div>
    @if(isset($related2TableId))
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@yield('related2-box-title') <em>@yield('box-title')</em></h3>

                    <div class="box-tools pull-right">

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="{{ $related2TableId }}" class="table table-hover table-bordered">
                        <thead>
                        @yield('related2-header-row')
                        </thead>
                        <tbody>
                        @yield('related2-rows')
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    @endif
</div>
@endsection

@push('js')
@if(isset($related2TableId))
@dtdefaults('{{ $relatedTableId }},{{ $related2TableId }}')
@else
@dtdefaults('{{ $relatedTableId or 'related-table' }}')
@endif
<script>
    $(function () {
        $('#{{ $relatedTableId or 'related-table' }}').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": {!! $relatedDefaultOrder or '[[0, "asc"]]' !!}
        });
        @if(isset($related2TableId))
        $('#{{ $related2TableId  }}').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": {!! $related2DefaultOrder or '[[0, "asc"]]' !!}
        });
        @endif
    });
</script>
@endpush