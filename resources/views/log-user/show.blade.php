@extends('wmcms::page')

@section('title', 'User Log - ' . $logUser->userAction->slug . '::' . $logUser->user->person->email)
@section('header-title')
    <h1>{{ $logUser->userAction->slug }}<small>{{ $logUser->created_at->format('m/d/Y h:i:sa') }}</small></h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('log-user.index') }}">User Log</a></li>
    <li class="active">{{ $logUser->created_at->format('m/d/Y h:i:sa') }}</li>
@endsection

@section('details')
    @include('wmcms::log-user.details')
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
            @include('wmcms::crud.show-box', [
                'boxTitle' => $logUser->user->person->email,
                'recordId' => $logUser->id
            ])
        </div>
        <div class="col-sm-6">
            @include('wmcms::crud.related-box', [
                'boxTitle' => 'Recent User Activity: <em>' . $logUser->user->person->email . '</em>',
                'defaultOrder' => '[[0, "desc"]]'
            ])
        </div>
    </div>
@endsection