@if(!empty($person['first_name']))
<div>
    <div>@include('wmcms::partials.name-full', ['person' => $person])</div>
    <div><span class="text-muted">{{ $person['email'] }}</span></div>
</div>
@else
    @if($formStatic)
        <p class="form-control-static">
    @endif
    {{ $person->email }}
    @if($formStatic)
        </p>
    @endif
@endif