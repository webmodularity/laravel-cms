<button type="button" class="btn btn-danger pull-left" id="delete-button-confirm"><i class="fa fa-times"></i>&nbsp;Delete</button>
@push('js')
    <script type="text/javascript">
        $(function() {
            $('#delete-button-confirm').click(function () {
                swal({
                        title: 'Delete This Record?',
                        text: '{{ $deleteButton['recordIdent'] }}',
                        type: 'error',
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Yes, delete it!',
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            url: '{{ $deleteButton['deleteUrl'] }}',
                            method: 'POST',
                            data: {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}',
                            },
                            dataType: 'json'
                        })
                            .done(function (response, status, xhr) {
                                swal({
                                        title: 'Record Deleted Successfully',
                                        text: 'The {{ $deleteButton['recordIdent'] }} record has been successfully removed.',
                                        type: 'success'
                                    },
                                    function() {
                                        window.location.replace("{{ $deleteButton['redirectUrl'] }}");
                                    });
                            })
                            .fail(function (xhr, status, error) {
                                toastr.error(JSON.parse(xhr.responseText.message));
                                swal.close();
                            });
                    }
                );
            });
        });
    </script>
@endpush