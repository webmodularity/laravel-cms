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

@section('related-user-social-logins-header')
    <tr>
        <th>Social</th>
        <th>User ID</th>
        <th>Email</th>
    </tr>
@endsection

@section('related-user-social-logins-rows')
    @foreach($user->socialProviders as $socialProvider)
        <tr>
            <td>{{ $socialProvider->getName() }}</td>
            <td>{{ $socialProvider->pivot->uid }}</td>
            <td>{{ $socialProvider->pivot->email }}</td>
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
            @include('wmcms::crud.related-box', [
                'boxTitle' => 'Social Logins: <em>' . $user->person->email . '</em>',
                'relatedTableId' => 'related-user-social-logins',
                'defaultOrder' => '[[3, "desc"]]'
            ])
        </div>
    </div>
@endsection