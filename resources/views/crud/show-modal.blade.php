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
            $(this).find("div.modal-body").LoadingOverlay("show",
                {
                    fontawesome: "fa fa-refresh fa-spin fa-2x fa-fw",
                    image: false
                }
            );
            var showId = $(event.relatedTarget).data('id');
            alert('{{ $showModalAjaxUrl }}/' + showId);
            console.log('{{ $showModalAjaxUrl }}/' + showId);
            $.ajax({
                type:"POST",
                url:'{{ $showModalAjaxUrl }}/' + showId,
                //data: '',
                dataType: 'json',
                success: function(data) {
                    toastr.success(data);
                    console.log(data);
                },
                error: function(data) {
                    toastr.error("An error occurred while fetching data!");
                    console.log(data);
                }
            })
        });
        $("#{{ $showModalId }}Modal").on('hide.bs.modal', function(event) {
            $(this).find("div.modal-body").LoadingOverlay("hide");
        });
    });
</script>
@endpush