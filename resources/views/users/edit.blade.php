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

@section('related-table-header')
    <tr>
        <th>ID</th>
        <th>Time</th>
        <th>IP</th>
        <th>Action</th>
    </tr>
@endsection

@section('related-table-rows')
    @foreach($userLogs as $userLog)
        <tr>
            <td><a href="{{ route('log-user.show', ['id' => $userLog->id]) }}">{{ $userLog->id }}</a></td>
            <td data-order="{{ $userLog->created_at->format('U') }}"
                data-sort="{{ $userLog->created_at->format('U') }}">
                {{ $userLog->created_at->format('m/d/Y h:i:sa') }}
            </td>
            <td>{{ $userLog->logRequest->ipAddress->ip }}</td>
            <td>{{ $userLog->userAction->slug }}</td>
        </tr>
    @endforeach
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6">
            @include('wmcms::crud.edit-box', [
                'boxTitle' => $user->person->email,
                'recordId' => $user->id
            ])
        </div>
        <div class="col-sm-6">
            @include('wmcms::users.edit-social-logins')
            @include('wmcms::crud.related-box', [
                'boxTitle' => 'Recent User Activity: <em>' . $user->person->email . '</em>',
                'defaultOrder' => '[[0, "desc"]]'
            ])
        </div>
    </div>
@endsection