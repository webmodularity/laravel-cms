@php
    $phoneTypeId = isset($phone['pivot']['phone_type_id']) ? $phone['pivot']['phone_type_id'] : 0;
    switch ($phoneTypeId)
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