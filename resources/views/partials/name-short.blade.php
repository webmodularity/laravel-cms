@if(isset($person) && !empty($person['first_name']))
{{ $person['first_name'] }} {{ $person['last_name'] }}
@endif