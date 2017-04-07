@if(isset($person) && !empty($person))
{{ $person['first_name'] }} {{ $person['last_name'] }}
@endif