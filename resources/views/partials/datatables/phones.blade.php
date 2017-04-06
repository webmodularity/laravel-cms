@foreach($phones as $phone)
    <i class="fa fa-fw fa-@php
    switch ($phone->pivot->phone_type_id)
    {
        case \WebModularity\LaravelContact\Phone::TYPE_MOBILE:
            echo "mobile-phone";
            break;
        case \WebModularity\LaravelContact\Phone::TYPE_FAX:
            echo "fax";
            break;
        case \WebModularity\LaravelContact\Phone::TYPE_HOME;
            echo "home";
            break;
        default:
            echo "phone";
    }
    @endphp"></i>
    @include('wmcms::partials.phone', ['phone' => $phone])
    @if(!$loop->last)
        <br />
    @endif
@endforeach