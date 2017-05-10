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
                            <th>User ID</th>
                            <th>Email</th>
                            <th style="width: 40px;" data-sortable="false">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->socialProviders as $socialProvider)
                            <tr>
                                <td>{{ $socialProvider->getName() }}</td>
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
                    <form action="{{ route('users.social.attach', ['user_id' => $user->id]) }}" method="post">
                    {!! csrf_field() !!}

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Add Social Login</button>
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
    });
</script>
@endpush