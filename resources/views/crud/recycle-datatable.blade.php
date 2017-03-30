@extends('wmcms::page')

@section('header-title')
    <h1>@yield('title')<small>Recycle Bin</small></h1>
@endsection

@section('breadcrumbs')
    <li class="active text-red">Recycle Bin</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="callout callout-danger">
                <h4>Recycle Bin</h4>

                <p>These records have been soft-deleted and DO NOT show up in results. There is no need to remove these records from the recycle bin unless you have a specific need to do so.</p>
            </div>
        </div>
    </div>
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">@yield('title') - Recycle Bin</h3>
            <div class="box-tools pull-right">

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {!! $dataTable->table(['class' => "table table-hover table-bordered"]) !!}
        </div>
        <!-- /.box-body -->
    </div>
@stop

@push('js')
@dtdefaults()
{!! $dataTable->scripts() !!}
@endpush