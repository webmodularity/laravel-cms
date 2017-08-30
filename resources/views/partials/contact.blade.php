<?php
$nameEmpty = empty($person['first_name']);
?>
<{{ $nameEmpty ? 'p' : 'div' }} class="{{ isset($class) && $class ? $class : ''}}">
    @if(!$nameEmpty)
        <div>
            @include('wmcms::partials.name-full', ['person' => $person])
        </div>
        <div>
    @endif

    @if(isset($link) && $link)
        <a href="{{ $link }}">
    @elseif(!$nameEmpty)
        <span class="text-muted">
    @endif
    {{ $person['email'] }}
    @if(isset($link) && $link)
        </a>
    @elseif(!$nameEmpty)
        </span>
    @endif

    @if(!$nameEmpty)
        </div>
    @endif
</{{ $nameEmpty ? 'p' : 'div' }}>