@if(!empty($person['first_name']))
<div>
    <div>@include('wmcms::partials.name-full', ['person' => $person])</div>
    <div><span class="text-muted">{{ $person['email'] }}</span></div>
</div>
@else
    @if(isset($formStatic) && $formStatic)
        <p class="form-control-static">
    @endif
    {{ $person->email }}
    @if(isset($formStatic) && $formStatic)
        </p>
    @endif
@endif