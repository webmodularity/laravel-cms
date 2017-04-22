@extends('wmcms::crud.edit-with-related')

@section('title', 'Person - ' . $person->email)
@section('box-title', $person->email)
@section('record-id', $person->id)

@section('header-title')
    <h1>Person Details</h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('people.index') }}">People</a></li>
    <li class="active">{{ $person->email }}</li>
@endsection

@section('form-action', route('people.update', ['id' => $person->id]))

@section('form')
    @include('people.form')
    @include('wmcms::partials.form.timestamps', [
                'created_at' => $person->created_at,
                'updated_at' => $person->updated_at
            ])
@endsection

@section('related-box-title', 'Equipment Requests:')
@var('relatedTableId', 'person-equipment-requests')
@var('relatedDefaultOrder', '[[3, "desc"]]')

@section('related-header-row')
    <tr>
        <th>ID</th>
        <th>Source</th>
        <th>Branch</th>
        <th>Approved At</th>
    </tr>
@endsection

@section('related-rows')
    @foreach($equipmentRequests as $equipmentRequest)
        <tr>
            <td>{{ $equipmentRequest->id }}</td>
            <td>{{ $equipmentRequest->source->slug }}</td>
            <td>{{ !is_null($equipmentRequest->branch) ? $equipmentRequest->branch->name : null }}</td>
            <td data-order="{{ !is_null($equipmentRequest->approved_at) ? $equipmentRequest->approved_at->format('U') : null }}"
                data-sort="{{ !is_null($equipmentRequest->approved_at) ? $equipmentRequest->approved_at->format('U') : null }}">
                {{!is_null($equipmentRequest->approved_at) ? $equipmentRequest->approved_at->format('m/d/Y h:i:sa') : null }}
            </td>
        </tr>
    @endforeach
@endsection

@section('related2-box-title', 'User Invitations:')
@var('related2TableId', 'person-user-invitations')
@var('related2DefaultOrder', '[[4, "desc"]]')

@section('related2-header-row')
    <tr>
        <th>ID</th>
        <th data-orderable="false">Social</th>
        <th data-orderable="false">Role</th>
        <th>Expires At</th>
        <th>Claimed At</th>
    </tr>
@endsection

@section('related2-rows')
    @foreach($person->userInvitations as $userInvitation)
        <tr>
            <td>{{ $userInvitation->id }}</td>
            <td>{{ $userInvitation->socialProvider->getName() }}</td>
            <td>{{ $userInvitation->role->slug }}</td>
            <td data-order="{{ !is_null($userInvitation->expires_at) ? $userInvitation->expires_at->format('U') : null }}"
                data-sort="{{ !is_null($userInvitation->expires_at) ? $userInvitation->expires_at->format('U') : null }}">
                {{!is_null($userInvitation->expires_at) ? $userInvitation->expires_at->format('m/d/Y h:i:sa') : null }}
            </td>
            <td data-order="{{ !is_null($userInvitation->claimed_at) ? $userInvitation->claimed_at->format('U') : null }}"
                data-sort="{{ !is_null($userInvitation->claimed_at) ? $userInvitation->claimed_at->format('U') : null }}">
                {{!is_null($userInvitation->claimed_at) ? $userInvitation->claimed_at->format('m/d/Y h:i:sa') : null }}
            </td>
        </tr>
    @endforeach
@endsection