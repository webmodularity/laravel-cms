@extends('wmcms::crud.show-with-related')

@section('title', 'User Log - ' . $logUser->user->person->email . ' ' . $logUser->logUserAction->slug)
@section('box-title', $logUser->created_at->format('m/d/Y h:i:sa'))
@section('record-id', $logUser->id)

@section('header-title')
    <h1>User Log Details</h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('user-log.index') }}">User Log</a></li>
    <li class="active">{{ $logUser->created_at->format('m/d/Y h:i:sa') }}</li>
@endsection


@section('details')
    @include('user-log.details')
    @include('wmcms::partials.form.timestamps', [
                'created_at' => $logUser->created_at
            ])
@endsection

@section('related-box-title', 'User Log:')
@var('relatedTableId', 'user-logs')
@var('relatedDefaultOrder', '[[3, "desc"]]')

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
            <td>{{ $userLog->id }}</td>
            <td data-order="{{ $userLog->created_at->format('U') }}"
                data-sort="{{ $userLog->created_at->format('U') }}">
                {{ $userLog->created_at->format('m/d/Y h:i:sa') }}
            </td>
            <td>{{ $userLog->ipAddress->ip }}</td>
            <td>{{ $userLog->logUserAction->slug }}</td>
        </tr>
    @endforeach
@endsection