@extends('wmcms::page')

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

@include('wmcms::users.related-table')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            @include('wmcms::crud.edit-box', [
                'boxTitle' => $user->person->email,
                'recordId' => $user->id,
                'deleteButton' => [
                    'recordIdent' => $user->person->email,
                    'deleteUrl' => route('users.destroy', ['id' => $user->id]),
                    'redirectUrl' => route('users.index')
                ]
            ])
        </div>
        <div class="col-sm-6">
            @include('wmcms::crud.related-box', [
                'boxTitle' => 'Recent User Activity',
                'relatedTableId' => 'userLog',
                'defaultOrder' => '[[0, "desc"]]'
            ])
            @include('wmcms::users.edit-social-logins')
        </div>
    </div>
    @include('wmcms::log-user.show-modal')
@endsection