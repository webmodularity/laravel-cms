<div class="box {{ isset($boxClass) ? $boxClass : 'box-primary' }}">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            @yield('box-tools')
            <label class="label label-primary">ID: {{ $recordId }}</label>
        </div><!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <form class="form-horizontal">
            @yield('form-show')
        </form>
    <!-- /.box-body -->
    </div>
</div>