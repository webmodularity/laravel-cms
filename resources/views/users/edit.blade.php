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
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->socialProviders as $socialProvider)
                            <tr>
                                <td>{{ $socialProvider->getName() }}</td>
                                <td>{{ $socialProvider->pivot->uid }}</td>
                                <td>{{ $socialProvider->pivot->email }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection

@push('js')
@dtdefaults()
<script>
    $(function () {
        $('#related-user-social-logins').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "asc"]]
        });
    });
</script>
@endpush