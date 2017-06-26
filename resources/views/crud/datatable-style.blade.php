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
</script>