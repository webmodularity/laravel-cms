<script>
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            lengthMenu: '<div class="btn-group" role="group">' +
            '<button type="button" class="btn btn-sm btn-default" id="dataTableLengthReset"><span class="text-muted">Results per page:</span></button>' +
            '_MENU_' +
            '</div>'
        },
        dom: "<'row'<'col-sm-8'B><'col-sm-4'<'pull-right'l>>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    $(function () {
        var dataTable = $('#dataTableBuilder').DataTable();
        $('#dataTableSearch').keyup(function() {
            dataTable.search($(this).val()).draw();
        });

        // lengthMenu
        var dataTableLengthSelect = $('#dataTableBuilder_length').find('select').selectpicker({
            style: 'btn-default btn-sm',
            width: 'fit',
            dropdownAlignRight: true
        });

        $('#dataTableLengthReset').click(function (event) {
            event.stopPropagation();
            dataTableLengthSelect.selectpicker('toggle');
        });

        // filter
        $('#dataTableFilterReset').click(function (event) {
            var searchInput = $('#dataTableSearch');
            searchInput.val('');
            searchInput.trigger('keyup');
        });

        $('#columnFilterHelper').click(function (event) {
            WMCMS.DT.FILTER.columnFilter(
                $(event.target).data("column-filter-name"),
                $(event.target).data("column-filter-value"),
                $(event.target).data("column-filter-replace") !== false
            );
        });
    });
</script>