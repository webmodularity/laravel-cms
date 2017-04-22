@extends('wmcms::crud.create-with-recent')

@section('title', 'Person - Create')
@section('box-title', 'Create Person')

@section('header-title')
    <h1>Person<small>Create New</small></h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('people.index') }}">People</a></li>
    <li class="active">Create New</li>
@endsection

@section('form-action', route('people.store'))

@section('form')
    @include('people.form')
@endsection

@section('recent-box-title', 'Recently Added People')

@section('recent-header-row')
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Name</th>
        <th>Created At</th>
    </tr>
@endsection

@section('recent-rows')
    @foreach($recentlyAdded as $recent)
        <tr>
            <td><a href="{{ route('people.edit', ['id' => $recent->id]) }}">{{ $recent->id }}</a></td>
            <td>{{ $recent->email }}</td>
            <td>@include('wmcms::partials.name-full', ['person' => $recent])</td>
            <td>{{ $recent->created_at->format('m/d/Y h:i:sa') }}</td>
        </tr>
    @endforeach
@endsection