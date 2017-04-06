@foreach($phones as $phone)
    {!! dd($phone) !!}
    @php
    switch ($phone->pivot['phone_type_id'])
    {
        case \WebModularity\LaravelContact\Phone::TYPE_MOBILE:
            $phoneIcon = "mobile-phone";
            break;
        case \WebModularity\LaravelContact\Phone::TYPE_FAX:
            $phoneIcon = "fax";
            break;
        case \WebModularity\LaravelContact\Phone::TYPE_HOME;
            $phoneIcon = "home";
            break;
        default:
            $phoneIcon = "phone";
    }
    @endphp
    <i class="fa fa-fw fa-{{ $phoneIcon }}"></i>
    @include('wmcms::partials.phone', ['phone' => $phone])
    @if(!$loop->last)
        <br />
    @endif
@endforeach