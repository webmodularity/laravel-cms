@extends('wmcms::page')

@section('content')
<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em>@yield('box-title')</em></h3>
                <div class="box-tools pull-right">
                    <label class="label label-primary">ID: @yield('record-id')</label>
                </div><!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="@yield('form-action')" method="post">
                {!! csrf_field() !!}
                {{ method_field('PUT') }}
                <div class="box-body">
                    @include('wmcms::partials.form.success')
                    @include('wmcms::partials.form.error-summary')
                    @yield('form')
                    <!-- /.box-body -->
                </div>

                <div class="box-footer">
                    <button type="reset" class="btn btn-default pull-left">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@yield('related-box-title') <em>@yield('box-title')</em></h3>

                <div class="box-tools pull-right">

                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="{{ $relatedTableId or 'related-table' }}" class="table table-hover table-bordered">
                    <thead>
                        @yield('related-header-row')
                    </thead>
                    <tbody>
                        @yield('related-rows')
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
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
@dtdefaults('{{ $relatedTableId or 'related-table' }}')
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