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
            @yield($relatedTableId or 'related-table' . '-header')
            </thead>
            <tbody>
            @yield($relatedTableId or 'related-table' . '-rows')
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>