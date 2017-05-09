@extends('wmcms::page')

@section('title', 'Users')
@section('header-title')
    <h1>Users<small>Active Records</small></h1>
@endsection

@section('breadcrumbs')
    <li class="active">Users</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('wmcms::crud.datatable-box', [
                'boxTitle' => 'Users'
            ])
        </div>
    </div>
@stop

@push('js')
@dtdefaults()
{!! $dataTable->scripts() !!}
@endpush