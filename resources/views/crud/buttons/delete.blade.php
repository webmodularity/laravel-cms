<button type="button" class="btn btn-danger pull-left" id="delete-button-confirm"><i class="fa fa-times"></i>&nbsp;Delete</button>
@push('js')
    <script type="text/javascript">
        $(function() {
            $('#delete-button-confirm').click(function () {
                swal({
                        title: 'Delete this Equipment Request?',
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
                                        window.location.replace("{{ $deleteButton['indexUrl'] }}");
                                        window.location.replace("{{ route('equipment.requests.index') }}");
                                    });
                            })
                            .fail(function (xhr, status, error) {
                                var errorResponse = JSON.parse(xhr.responseText) ? JSON.parse(xhr.responseText) : 'An unknown server error was encountered when attempting to delete this record.';
                                toastr.error(errorResponse);
                                swal.close();
                            });
                    }
                );
            });
        });
    </script>
@endpush