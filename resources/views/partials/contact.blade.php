@if(!empty($person['first_name']))
<div>
    <div>@include('wmcms::partials.name-full', ['person' => $person])</div>
    <div><span class="text-muted">{{ $person['email'] }}</span></div>
</div>
@else
    <p class="form-control-static">{{ $person->email }}</p>
@endif