<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            <label class="label label-primary">ID: @yield('record-id')</label>
        </div><!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal">
        <div class="box-body">
        @yield('details')
        <!-- /.box-body -->
        </div>
    </form>
</div>