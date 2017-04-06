<?php
use WebModularity\LaravelContact\Phone;
?>
@foreach($phones as $phone)
    <i class="fa fa-fw fa-<?php
    if (Phone::TYPE_MOBILE) {
        echo "mobile-phone";
    } elseif (Phone::TYPE_FAX) {
        echo "fax";
    } elseif (Phone::TYPE_HOME) {
        echo "home";
    } else {
        echo "phone";
    }
    ?>"></i>&nbsp;
    @include('wmcms::partials.phone', ['phone' => $phone])
@endforeach