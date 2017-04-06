@foreach($phones as $phone)
    {!! var_dump($phone) !!}
    <i class="fa fa-fw fa-phone"></i>
    @include('wmcms::partials.phone', ['phone' => $phone])
    @if(!$loop->last)
        <br />
    @endif
@endforeach