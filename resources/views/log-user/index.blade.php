@extends('wmcms::page')

@section('title', 'User Log')
@section('header-title')
    <h1>User Log</h1>
@endsection

@section('breadcrumbs')
    <li class="active">User Log</li>
@endsection

@section('content')
    @include('wmcms::crud.datatable-box', [
        'boxTitle' => 'User Log'
    ])
@stop

@push('js')
@dtdefaults()
{!! $dataTable->scripts() !!}
@endpush