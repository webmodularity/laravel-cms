<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle or 'Related' !!}</h3>
        <div class="box-tools pull-right">

        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="{{ $relatedTableId or 'related-table' }}" class="table table-hover table-bordered">
            <thead>
            @yield($relatedTableId . '-header')
            </thead>
            <tbody>
            @yield($relatedTableId . '-rows')
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>