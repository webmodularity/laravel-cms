@extends('wmcms::crud.edit-with-related')

@section('title', 'User - ' . $user->person->email)
@section('box-title', $user->person->email)
@section('record-id', $user->id)

@section('header-title')
    <h1>User Details</h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="active">{{ $user->person->email }}</li>
@endsection

@section('form-action', route('users.update', ['id' => $user->id]))

@section('form')
    @include('wmcms::users.form')
    @include('wmcms::partials.form.timestamps', [
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ])
@endsection