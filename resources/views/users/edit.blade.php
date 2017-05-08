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

@section('content')
    <div class="row">
        <div class="col-sm-6">
            @include('wmcms::crud.edit-box')
        </div>
        <div class="col-sm-6">
            @include('wmcms::crud.related-box', [
                'boxTitle' => 'Social Logins <em>' . $user->person->email . '</em>',
                'relatedTableId' => 'related-user-social-logins'
            ])
            @section('related-user-social-logins-header')
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
        </div>
    </div>
@endsection