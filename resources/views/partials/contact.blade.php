@if(!empty($person['first_name']))
<div>
    <div>@include('wmcms::partials.name-full', ['person' => $person])</div>
    <div><span class="text-muted">{{ $person['email'] }}</span></div>
</div>
@else
    {{ $person->email }}
@endif