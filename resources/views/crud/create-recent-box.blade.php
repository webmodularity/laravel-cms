<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>

        <div class="box-tools pull-right">
            @yield('box-tools')
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="recently-added" class="table table-hover table-bordered">
            <thead>
            @yield('recent-header')
            </thead>
            <tbody>
            @yield('recent-rows')
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>