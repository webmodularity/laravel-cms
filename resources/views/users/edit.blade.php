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
                    <table id="related-user-social-logins" class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Social</th>
                            <th data-sortable="false">Avatar</th>
                            <th>User ID</th>
                            <th>Email</th>
                            <th style="width: 40px;" data-sortable="false">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->socialProviders as $socialProvider)
                            <tr>
                                <td>{{ $socialProvider->getName() }}</td>
                                <td>@if(!empty($socialProvider->pivot->avatar_url))
                                        <img src="{{ $socialProvider->pivot->avatar_url }}" width="40" height="40" title="{{ $socialProvider->pivot->avatar_url }}" />
                                    @else
                                        <em>None</em>
                                    @endif
                                </td>
                                <td>{{ $socialProvider->pivot->uid }}</td>
                                <td>{{ $socialProvider->pivot->email }}</td>
                                <td>@include('wmcms::crud.actions.delete', [
                                    'id' => $socialProvider->id,
                                    'recordIdent' => $socialProvider->getName()
                                ])</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
                    <form action="{{ route('users.social.attach', ['user_id' => $user->id]) }}" method="post" id="addSocialLoginForm">
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
                    <button type="submit" class="btn btn-primary" id="addSocialLoginSubmit">Add Social Login</button>
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
        var datatable = $('#related-user-social-logins').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "asc"]]
        });

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
                            swal({
                                    title: 'Successfully Unlinked Social Login',
                                    text: response,
                                    type: 'success',
                                    confirmButtonClass: 'btn-primary',
                                },
                                function() {
                                    datatable.row(row).remove().draw();
                                });
                        },
                        error: function (xhr, status, error) {
                            swal({
                                title: 'Delete Failed!',
                                text: JSON.parse(xhr.responseText)
                                    || 'An unknown server error was encountered when attempting to delete this record.',
                                type: 'error',
                                confirmButtonClass: 'btn-primary',
                            });
                        }
                    });
                });
        });

        $('#addSocialLoginForm').on('submit', function(event) {
            event.preventDefault();
            var form = $(this);
            var submitButton = form.find("#addSocialLoginSubmit");
            var formGroups = form.find("div.form-group");
            formGroups.removeClass('has-error');
            formGroups.find("span.help-block").remove();
            console.log(submitButton);
            submitButton.text('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> <span class="sr-only">Loading...</span>');
            $.ajax({
                type:"POST",
                url:'{{ route('users.social.attach', ['user_id' => $user->id]) }}',
                data:$(this).serialize(),
                dataType: 'json',
                success: function(data){
                    console.log(data.responseJSON);
                },
                error: function(data){
                    $.each(data.responseJSON, function (index, value) {
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