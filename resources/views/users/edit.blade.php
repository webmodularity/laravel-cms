@extends('wmcms::page')

@section('title', 'User - ' . $user->person->email)
@section('box-title', $user->person->email)
@section('record-id', $user->id)

@section('header-title')
    <h1>User Details</h1>
@endsection

@section('breadcrumbs')
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="active">{{ $user->person->email }}</li>
@endsection

@section('form-action', route('users.update', ['id' => $user->id]))
@section('form')
    @include('wmcms::users.form')
    @include('wmcms::partials.form.timestamps', [
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ])
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6">
            @include('wmcms::crud.edit-box', [
                'boxTitle' => $user->person->email,
                'recordId' => $user->id
            ])
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Social Logins: <em>{{ $user->person->email }}</em></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSocialLogin"><span class="fa fa-plus"></span>&nbsp;Add<span class="hidden-xs hidden-sm"> Social Login</span></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="userSocialLoginTable" class="table table-hover table-bordered"></table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <div id="addSocialLogin" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add Social Login</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.social.attach', ['user_id' => $user->id]) }}" method="post" id="addUserSocialLoginForm">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="control-label">User</label>
                            <p>{{ $user->person->email }}</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="social_provider_id">Social Provider</label>
                            <select class="form-control" style="width: 100%;" name="social_provider_id" id="social_provider_id" required>
                                @foreach($socialProviders as $socialProvider)
                                    <option value="{{ $socialProvider['id'] }}"{{ old('social_provider_id') == $socialProvider['id'] ? ' selected' : '' }}>{{ $socialProvider->getName() }}</option>
                                @endforeach()
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="uid">Social User ID</label>
                            <input type="text" name="uid" class="form-control" placeholder="Social User ID" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="email">Social User Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="avatar_url">Avatar URL (Optional)</label>
                            <input type="url" name="avatar_url" class="form-control" placeholder="Avatar URL" />
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="addUserSocialLoginSubmit">Add Social Login</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('js')
<script>
    $(function () {
        var userSocialLoginData = [
            @foreach($user->socialProviders as $socialProvider)
            [
                "{{ $socialProvider->id }}",
                "{{ $socialProvider->getName() }}",
                "{{ $socialProvider->pivot->avatar_url }}",
                "{{ $socialProvider->pivot->uid }}",
                "{{ $socialProvider->pivot->email }}"
            ]
            @endforeach
        ];
        var userSocialLoginDataTable = $('#userSocialLoginTable').DataTable({
            data: userSocialLoginData,
            columns: [
                { visible: false },
                { title: "Social" },
                { title: "Avatar", orderable: false, searchable: false },
                { title: "User ID" },
                { title: "Email" },
                { title: "Delete", orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    render: function (data, type, row) {
                        if (data) {
                            return '<img src="' + data + '" width="40" height="40" title="' + data + '" />';
                        } else {
                            return '<em>None</em>';
                        }
                    },
                    targets: 2
                },
                {
                    render: function (data, type, row) {
                        return '<button type="button" class="btn btn-xs btn-danger delete-confirm-button" data-id="'+row[0]+'" data-token="{{ csrf_token() }}" data-record-ident="'+row[1]+'"><i class="fa fa-trash-o"></i>&nbsp;Delete</button>';
                    },
                    width: "40px",
                    targets: 5
                }
            ],
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "drawCallback": function (settings) {
                $('.delete-confirm-button').click(function(){
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
                                url: location.pathname.replace(/\/?$/, '') + '/social-logins/' +id,
                                method: 'POST',
                                data: {
                                    '_method': 'DELETE',
                                    '_token': token,
                                },
                                success: function (response) {
                                    userSocialLoginDataTable.row(row).remove().draw();
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

        $('#addUserSocialLoginForm').on('submit', function(event) {
            event.preventDefault();
            var form = $(this);
            var submitButton = $("#addUserSocialLoginSubmit");
            var formGroups = form.find("div.form-group");
            formGroups.removeClass('has-error');
            formGroups.find("span.help-block").remove();
            var submitHtmlOrig = submitButton.html();
            submitButton.html('<i class="fa fa-spinner fa-pulse"></i>&nbsp;Saving... <span class="sr-only">Saving...</span>');
            submitButton.prop('disabled', true);
            $.ajax({
                type:"POST",
                url:'{{ route('users.social.attach', ['user_id' => $user->id]) }}',
                data:$(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    toastr.success(data);
                    submitButton.html('<i class="fa fa-check"></i>&nbsp;Saved<span class="sr-only">Saved</span>');
                    submitButton.removeClass("btn-primary").addClass("btn-success");
                    var socialProviderSelected = form.find("#social_provider_id option:checked");
                    var socialProviderId = socialProviderSelected.val();
                    var socialProviderName = socialProviderSelected.text();
                    var avatarUrlVal = form.find("input[name=avatar_url]").val();
                    var uidVal = form.find("input[name=uid]").val();
                    var emailVal = form.find("input[name=email]").val();
                    userSocialLoginDataTable.row.add([socialProviderId, socialProviderName, avatarUrlVal, uidVal, emailVal]).draw();
                    setTimeout(function() {
                        submitButton.html(submitHtmlOrig);
                        submitButton.removeClass("btn-success").addClass("btn-primary");
                        submitButton.prop('disabled', false);
                        form.trigger("reset");
                        $("#addSocialLogin").modal('hide');
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