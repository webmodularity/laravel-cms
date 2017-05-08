<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><em>@yield('box-title')</em></h3>
        <div class="box-tools pull-right">
            <label class="label label-primary">ID: @yield('record-id')</label>
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
            <button type="reset" class="btn btn-default pull-left">Cancel</button>
            <button type="submit" class="btn btn-primary pull-right">Update</button>
        </div>
    </form>
</div>