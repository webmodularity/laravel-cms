@if(!empty($user->person->first_name))
    <div class="{{ isset($formStatic) && $formStatic ? 'form-control-static' : ''}}">
        <div>@include('wmcms::partials.name-full', ['person' => $user->person])</div>
        <div><a href="{{ route('users.edit', ['id' => $user->id]) }}">{{ $user->person->email }}</a></div>
    </div>
@else
    <p class="{{ isset($formStatic) && $formStatic ? 'form-control-static' : ''}}"><a href="{{ route('users.edit', ['id' => $user->id]) }}">{{ $user->person->email }}</a></p>
@endif