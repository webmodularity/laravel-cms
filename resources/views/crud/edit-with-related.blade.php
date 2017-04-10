@extends('wmcms::page')

@section('content')
<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><em>@yield('box-title')</em></h3>
                <div class="box-tools pull-right">
                    <label class="label label-primary">ID: @yield('record-id')</label>
                </div><!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="@yield('form-action')" method="post">
                {!! csrf_field() !!}
                {{ method_field('PUT') }}
                <div class="box-body">
                    @include('wmcms::partials.form.success')
                    @include('wmcms::partials.form.error-summary')
                    @yield('form')
                    <!-- /.box-body -->
                </div>

                <div class="box-footer">
                    <button type="reset" class="btn btn-default pull-left">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@yield('related-box-title') <em>@yield('box-title')</em></h3>

                <div class="box-tools pull-right">

                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="@yield('related-table-id')" class="table table-hover table-bordered">
                    <thead>
                        @yield('related-header-rows')
                    </thead>
                    <tbody>
                        @yield('related-rows')
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
</div>
@endsection