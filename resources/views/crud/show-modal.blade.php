<?php
$showModalId = isset($showModalId) && !empty($showModalId)
    ? $showModalId
    : 'showModal';
$showModalHeader = isset($showModalHeader)
    ? $showModalHeader
    : 'Details';
$showModalSizeClass = isset($showModalSize) && in_array($showModalSize, ['lg', 'sm'])
    ? ' modal-' . $showModalSize
    : '';
$showModalAjaxUrl = isset($showModalAjaxUrl)
    ? $showModalAjaxUrl
    : route(preg_replace("/\.".Route::current()->getActionMethod()."$/", '.index', Route::current()->getName()));
?>
<div id="{{ $showModalId }}Modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog{{ $showModalSizeClass }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{!! $showModalHeader !!}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    @yield($showModalId . 'Form')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('js')
<script>
    $(function () {
        $("#{{ $showModalId }}Modal").on('shown.bs.modal', function(event) {
            var modalBody = $(this).find("div.modal-body");
            modalBody.LoadingOverlay("show",
                {
                    fontawesome: "fa fa-refresh fa-spin fa-2x fa-fw",
                    image: false
                }
            );
            var showId = $(event.relatedTarget).data('id');
            $.ajax({
                url:'{{ $showModalAjaxUrl }}/' + showId,
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, value) {
                        if (value === null) {
                            modalBody.find("#{{ $showModalId }}" + _.upperFirst(index)).html('<span class="text-muted"><em>None</em></span>');
                        } else {
                            modalBody.find("#{{ $showModalId }}" + _.upperFirst(index)).html(value);
                        }
                    });
                    modalBody.LoadingOverlay("hide");
                },
                error: function(data) {
                    console.log(data);
                    toastr.error("An error occurred while fetching data!");
                    modalBody.LoadingOverlay("hide");
                }
            })
        });
    });
</script>
@endpush