<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            @yield('box-tools')
            <label class="label label-primary">ID: {{ $recordId }}</label>
        </div><!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form action="@yield('form-action')" method="post">
        {!! csrf_field() !!}
        {{ method_field('PUT') }}
        <div class="box-body">
            @include('wmcms::partials.form.success')
            @include('wmcms::partials.form.error-summary')
            @yield('form')
        <!-- /.box-body -->
        </div>

        <div class="box-footer">
            @if (isset($deleteButton) && !empty($deleteButton))
                @if (is_array($deleteButton))
                    @include('wmcms::crud.buttons.delete', ['deleteButton' => $deleteButton])
                @else
                    @include($deleteButton)
                @endif
            @endif

            @if (isset($updateButton) && !empty($updateButton))
                @include('wmcms::crud.buttons.update')
            @else
                @include($updateButton)
            @endif
        </div>
    </form>
</div>

