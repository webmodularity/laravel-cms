@extends('wmcms::page')

@section('header_title')
    <h1>@yield('title')<small>Active Records</small></h1>
@endsection

@section('breadcrumbs')
    <li class="active">@yield('title')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@yield('title')</h3>
                    <div class="box-tools pull-right">

                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <!-- /.box-header -->
                <div class="box-body">
                    {!! $dataTable->table(['class' => "table table-hover table-bordered"]) !!}
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@stop

@push('js')
@dtdefaults()
{!! $dataTable->scripts() !!}
@endpush