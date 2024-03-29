@extends('wmcms::page')

@section('title', 'User Log')
@section('header-title')
    <h1>User Log</h1>
@endsection

@section('breadcrumbs')
    <li class="active">User Log</li>
@endsection

@section('box-tools')
    @include('wmcms::log-user.filter')
@endsection

@section('content')
    @include('wmcms::crud.datatable-box', [
        'boxTitle' => 'User Log'
    ])
    @include('wmcms::log-user.show-modal')
@stop