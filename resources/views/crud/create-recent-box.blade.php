@push('js')
<script>
    $(function () {
        WMCMS.DT.TABLES['recent'] = $('#recent').DataTable({
            data: [
                @yield('recentData')
            ],
            columns: [
                @yield('recentColumns')
            ],
            columnDefs: [
                @yield('recentColumnDefs')
            ],
            order: {!! $defaultOrder or '[[0, "asc"]]' !!}
        });
    });
</script>
@endpush

@include('wmcms::crud.datatable-box-mini', [
    'tableId' => 'recent'
])