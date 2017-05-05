@extends('wmcms::page')

@section('content')
<div class="row">
    <div class="col-sm-6">
        @yield('parent-container')
    </div>
    <div class="col-sm-6">
        @yield('child-container')
    </div>
</div>
@endsection