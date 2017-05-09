<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>

        <div class="box-tools pull-right">
            @yield('box-tools')
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form action="@yield('form-action')" method="post">
        {!! csrf_field() !!}

        <div class="box-body">
        @include('wmcms::partials.form.success')
        @include('wmcms::partials.form.error-summary')
        @yield('form')
        <!-- /.box-body -->
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Create</button>
        </div>
    </form>
</div>