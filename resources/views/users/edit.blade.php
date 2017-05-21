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

@section('userLogColumns')
    { title: "Time" },
    { title: "IP" },
    { title: "Action" },
    { title: "Show", orderable: false, searchable: false }
@endsection

@section('userLogData')
    @foreach($userLogs as $userLog)
        [
            "{{ $userLog->created_at->format('m/d/Y h:i:sa') }}",
            "{{ $userLog->logRequest->ipAddress->ip }}",
            "{{ $userLog->userAction->slug }}",
            "{{ $userLog->id }}"
        ],
    @endforeach
@endsection

@section('userLogColumnDefs')
    {
        render: function (data, type, row) {
            return '<a href="{{ route('log-user.index') }}/"+data+"" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
        },
        width: "20px",
        targets: 3
    }
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
                'boxTitle' => 'Recent User Activity',
                'relatedTableId' => 'userLog',
                'defaultOrder' => '[[3, "desc"], [0, "desc"]]'
            ])
        </div>
    </div>
@endsection