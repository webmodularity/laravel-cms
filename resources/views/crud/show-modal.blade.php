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
                <div class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                    <form class="form-horizontal">
                        @yield($showModalId . 'Form')
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>