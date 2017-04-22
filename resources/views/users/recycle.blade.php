@extends('wmcms::crud.recycle-datatable')

@section('title', 'Users - Recycle Bin')
@section('box-title', 'Users - Recycle Bin')

@section('header-title')
    <h1>Users<small>Recycle Bin</small></h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="active text-red">Recycle Bin</li>
@endsection