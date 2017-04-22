@extends('wmcms::crud.recycle-datatable')

@section('title', 'People - Recycle Bin')
@section('box-title', 'People - Recycle Bin')

@section('header-title')
    <h1>People<small>Recycle Bin</small></h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('people.index') }}">People</a></li>
    <li class="active text-red">Recycle Bin</li>
@endsection