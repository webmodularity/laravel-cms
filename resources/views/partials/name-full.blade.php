@if(isset($person) && !empty($person['first_name']))
{{ $person['first_name'] }} {{ !empty($person['middle_name']) ? $person['middle_name'] . ' ' : '' }} {{ $person['last_name'] }}
@endif