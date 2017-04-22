@extends('wmcms::crud.create-with-recent')

@section('title', 'User - Create')
@section('box-title', 'Create User')

@section('header-title')
    <h1>User<small>Create New</small></h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="active">Create New</li>
@endsection

@section('form-action', route('users.store'))

@section('form')
    @include('users.form')
@endsection

@section('recent-box-title', 'Recently Added Users')

@section('recent-header-row')
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Role</th>
        <th>Name</th>
        <th>Created At</th>
    </tr>
@endsection

@section('recent-rows')
    @foreach($recentlyAdded as $recent)
        <tr>
            <td><a href="{{ route('users.edit', ['id' => $recent->id]) }}">{{ $recent->id }}</a></td>
            <td>{{ $recent->person->email }}</td>
            <td>{{ studly_case($recent->role->slug) }}</td>
            <td>@include('wmcms::partials.name-full', ['person' => $recent->person])</td>
            <td>{{ $recent->created_at->format('m/d/Y h:i:sa') }}</td>
        </tr>
    @endforeach
@endsection