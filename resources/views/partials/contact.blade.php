@if(!empty($person['first_name']))
    <div class="{{ isset($formStatic) && $formStatic ? 'form-control-static' : ''}}">
        <div>@include('wmcms::partials.name-full', ['person' => $person])</div>
        <div><span class="text-muted">{{ $person['email'] }}</span></div>
    </div>
@else
    <p class="{{ isset($formStatic) && $formStatic ? 'form-control-static' : ''}}">{{ $person->email }}</p>
@endif