@extends('wmcms::page')

@section('title', 'User - Create')
@section('header-title')
    <h1>User<small>Create New</small></h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="active">Create New</li>
@endsection

@section('form-action', route('users.store'))
@section('form')
    @include('wmcms::users.form')
@endsection

@section('recentColumns')
    { title: "ID" },
    { title: "Person" },
    { title: "Role" },
    { title: "Created At" },
    { title: "Edit", orderable: false, searchable: false }
@endsection

@section('recentData')
    @foreach($recentlyAdded as $recent)
        [
        {{ $recent->id }},
        "{{ $recent->person->email }}",
        "{{ studly_case($recent->role->slug) }}",
        "{{ $recent->created_at->format('m/d/Y h:i:sa') }}",
        {{ $recent->id }},
        "@include('wmcms::partials.name-full', ['person' => $recent->person])"
        ],
    @endforeach
@endsection

@section('recentColumnDefs')
    {
    render: function (data, type, row) {
    return '<a href="{{ route('users.index') }}/'+data+'/edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
    },
    width: "20px",
    "className": "text-center",
    targets: 4
    },
    {
    render: function (data, type, row) {
        return WMCMS.DT.RENDER.contact(data, row[5]);
    },
    targets: 1
    }
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            @include('wmcms::crud.create-box', [
                'boxTitle' => 'Create User'
            ])
        </div>
        <div class="col-lg-6">
            @include('wmcms::crud.create-recent-box', [
                'boxTitle' => 'Recently Added Users',
                'defaultOrder' => '[[3, "desc"]]'
            ])
        </div>
    </div>
@endsection