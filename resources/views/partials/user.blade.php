@if(!empty($user->person->first_name))
    <div>
        <div>@include('wmcms::partials.name-full', ['person' => $user->person])</div>
        <div><a href="{{ route('users.edit', ['id' => $user->id]) }}">{{ $user->person->email }}</a></div>
    </div>
@else
    <a href="{{ route('users.edit', ['id' => $user->id]) }}">{{ $user->person->email }}</a>
@endif