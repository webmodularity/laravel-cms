<?php
$relatedAjaxTableId = isset($relatedAjaxTableId) && !empty($relatedAjaxTableId)
    ? $relatedAjaxTableId
    : 'relatedAjaxTable';
$relatedAjaxTableHeader = isset($relatedAjaxTableHeader)
    ? $relatedAjaxTableHeader
    : 'Create New Record';
?>
@section('box-tools')
    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#{{ $relatedAjaxTableId }}Modal"><span class="fa fa-plus"></span></button>
@endsection

<div id="{{ $relatedAjaxTableId }}Modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ $relatedAjaxTableHeader }}</h4>
            </div>
            <div class="modal-body">
                <form id="{{ $relatedAjaxTableId }}Form">
                    {!! csrf_field() !!}
                    @yield($relatedAjaxTableId . 'Form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="{{ $relatedAjaxTableId }}Submit">Save</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('js')
<script>
    $(function () {
        WMCMS.DT.TABLES['{{ $relatedAjaxTableId }}'] = $('#{{ $relatedAjaxTableId }}').DataTable({
            order: {!! $defaultOrder or '[[0, "asc"]]' !!},
            data: [
                @yield($relatedAjaxTableId . 'Data')
            ],
            columns: [
                @yield($relatedAjaxTableId . 'Columns')
            ],
            columnDefs: [
                @yield($relatedAjaxTableId . 'ColumnDefs')
            ],
            drawCallback: function (settings) {
                $("#{{ $relatedAjaxTableId }}").find(".delete-confirm-button").click(function(){
                    var id = $(this).data("id");
                    var token = $(this).data("token");
                    var recordIdent = $(this).data("record-ident");
                    var row = $(this).parents('tr');
                    swal({
                            title: 'Delete This Record?',
                            text: recordIdent,
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonClass: 'btn-danger',
                            confirmButtonText: 'Yes, delete it!',
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        },
                        function() {
                            $.ajax({
                                url: {!! $deleteUrl !!},
                                method: 'POST',
                                data: {
                                    '_method': 'DELETE',
                                    '_token': token,
                                },
                                success: function (response) {
                                    WMCMS.DT.TABLES['{{ $relatedAjaxTableId }}'].row(row).remove().draw();
                                    toastr.success(response);
                                    swal.close();
                                },
                                error: function (xhr, status, error) {
                                    toastr.error(JSON.parse(xhr.responseText) || 'An unknown server error was encountered when attempting to delete this record.');
                                    swal.close();
                                }
                            });
                        });
                });
            }
        });

        $('#{{ $relatedAjaxTableId }}Form').on('submit', function(event) {
            event.preventDefault();
            var form = $(this);
            var submitButton = $("#{{ $relatedAjaxTableId }}Submit");
            var formGroups = form.find("div.form-group");
            formGroups.removeClass('has-error');
            formGroups.find("span.help-block").remove();
            var submitHtmlOrig = submitButton.html();
            submitButton.html('<i class="fa fa-spinner fa-pulse"></i>&nbsp;Saving... <span class="sr-only">Saving...</span>');
            submitButton.prop('disabled', true);
            $.ajax({
                type:"POST",
                url:'{!! $postUrl !!}',
                data:$(this).serialize(),
                dataType: 'json',
                success: function(data, status, jqxhr) {
                    toastr.success(data.message);
                    submitButton.html('<i class="fa fa-check"></i>&nbsp;Saved<span class="sr-only">Saved</span>');
                    submitButton.removeClass("btn-primary").addClass("btn-success");
                    @yield($relatedAjaxTableId . 'RowAddData')
                        WMCMS.DT.TABLES['{{ $relatedAjaxTableId }}'].row.add(rowAddData).draw();
                    setTimeout(function() {
                        submitButton.html(submitHtmlOrig);
                        submitButton.removeClass("btn-success").addClass("btn-primary");
                        submitButton.prop('disabled', false);
                        form.trigger("reset");
                        $("#{{ $relatedAjaxTableId }}Modal").modal('hide');
                    }, 1000);
                },
                error: function(jqxhr, status, error) {
                    console.log(jqhxr);
                    toastr.error(status + ': ' + error);
                    submitButton.html(submitHtmlOrig);
                    submitButton.prop('disabled', false);
                    $.each(jqxhr.responseJSON, function (index, value) {
                        var errorFormGroup = form.find(":input[name='"+index+"']").parent('div.form-group');
                        errorFormGroup.addClass('has-error');
                        errorFormGroup.append("<span class=\"help-block\">"+value+"</span>");
                    });
                }
            })
        });
    });
</script>
@endpush

@include('wmcms::crud.datatable-box-mini', [
    'tableId' => $relatedAjaxTableId
])