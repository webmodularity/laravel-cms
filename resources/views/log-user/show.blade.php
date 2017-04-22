@extends('wmcms::crud.show-with-related')

@section('title', 'User Log - ' . $logUser->userAction->slug . '::' . $logUser->user->person->email)
@section('box-title', $logUser->user->person->email)
@section('record-id', $logUser->id)

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

@section('related-box-title', 'Recent User Activity:')
@var('relatedTableId', 'user-logs')
@var('relatedDefaultOrder', '[[0, "desc"]]')

@section('related-header-row')
    <tr>
        <th>ID</th>
        <th>Time</th>
        <th>IP</th>
        <th>Action</th>
    </tr>
@endsection

@section('related-rows')
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