<?php
$relatedAjaxTableId = isset($relatedAjaxTableId) && !empty($relatedAjaxTableId)
    ? $relatedAjaxTableId
    : 'relatedAjaxTable';
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add{{ $relatedAjaxTableId }}"><span class="fa fa-plus"></span>&nbsp;{{ $addText }}</button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="{{ $relatedAjaxTableId }}Table" class="table table-hover table-bordered"></table>
    </div>
    <!-- /.box-body -->
</div>

<div id="add{{ $relatedAjaxTableId }}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ $addText }}</h4>
            </div>
            <div class="modal-body">
                <form id="add{{ $relatedAjaxTableId }}Form">
                    {!! csrf_field() !!}
                    @yield($relatedAjaxTableId . 'Form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="add{{ $relatedAjaxTableId }}Submit">{{ $addText }}</button>
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
        var {{ $relatedAjaxTableId }}Data = [
            @yield($relatedAjaxTableId . 'Data')
        ];
        var {{ $relatedAjaxTableId }}DataTable = $('#{{ $relatedAjaxTableId }}Table').DataTable({
            data: {{ $relatedAjaxTableId }}Data,
            columns: [
                @yield($relatedAjaxTableId . 'Columns')
            ],
            columnDefs: [
                @yield($relatedAjaxTableId . 'ColumnDefs')
            ],
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": {!! $order !!},
            "drawCallback": function (settings) {
                $('.{{ $relatedAjaxTableId }}-delete-confirm-button').click(function(){
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
                                    {{ $relatedAjaxTableId }}DataTable.row(row).remove().draw();
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
            var submitButton = $("#add{{ $relatedAjaxTableId }}Submit");
            var formGroups = form.find("div.form-group");
            formGroups.removeClass('has-error');
            formGroups.find("span.help-block").remove();
            var submitHtmlOrig = submitButton.html();
            submitButton.html('<i class="fa fa-spinner fa-pulse"></i>&nbsp;Saving... <span class="sr-only">Saving...</span>');
            submitButton.prop('disabled', true);
            $.ajax({
                type:"POST",
                url:'{!! $postUrl !!},
                data:$(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    toastr.success(data);
                    submitButton.html('<i class="fa fa-check"></i>&nbsp;Saved<span class="sr-only">Saved</span>');
                    submitButton.removeClass("btn-primary").addClass("btn-success");
                    @yield($relatedAjaxTableId . 'RowAddData')
                    {{ $relatedAjaxTableId }}DataTable.row.add(rowAddData).draw();
                    setTimeout(function() {
                        submitButton.html(submitHtmlOrig);
                        submitButton.removeClass("btn-success").addClass("btn-primary");
                        submitButton.prop('disabled', false);
                        form.trigger("reset");
                        $("#add{{ $relatedAjaxTableId }}").modal('hide');
                    }, 1000);
                },
                error: function(data) {
                    toastr.error("An error occurred while saving. Please correct the errors and resubmit.");
                    submitButton.html(submitHtmlOrig);
                    submitButton.prop('disabled', false);
                    $.each(data.responseJSON, function (index, value) {
                        console.log(index);
                        console.log(value);
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