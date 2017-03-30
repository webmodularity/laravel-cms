@extends('page')

@section('title', {{ $title }})

@section('content_header')
    <h1>{{ $title }}<small>Active Records</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $title }}</h3>
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