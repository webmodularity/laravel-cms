@extends('wmcms::page')

@section('title', 'Users')
@section('header-title')
    <h1>Users<small>Active Records</small></h1>
@endsection

@section('breadcrumbs')
    <li class="active">Users</li>
@endsection

@section('box-tools')
    @include('wmcms::users.filter')
@endsection

@section('content')
    @include('wmcms::crud.datatable-box', [
        'boxTitle' => 'Users'
    ])
@stop