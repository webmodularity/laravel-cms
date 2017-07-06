@extends('wmcms::page')

@section('title', 'Users - Recycle Bin')
@section('header-title')
    <h1>Users<small>Recycle Bin</small></h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="active text-red">Recycle Bin</li>
@endsection

@section('box-tools')
    @include('wmcms::users.filter')
@endsection

@section('content')
    @include('wmcms::crud.recycle-warning')
    @include('wmcms::crud.recycle-datatable-box', [
        'boxTitle' => 'Users - Recycle Bin'
    ])
@stop