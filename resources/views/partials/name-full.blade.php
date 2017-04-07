@if(isset($person) && !empty($person))
{{ $person['first_name'] }} {{ !empty($person['middle_name']) ? $person['middle_name'] . ' ' : '' }} {{ $person['last_name'] }}
@endif