@extends('wmcms::page')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@yield('box-title')</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="@yield('form-action')" method="post">
                    {!! csrf_field() !!}

                    <div class="box-body">
                    @include('wmcms::partials.form.error-summary')
                    @yield('form')
                    <!-- /.box-body -->
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Create</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@yield('recent-box-title')</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="recently-added" class="table table-hover table-bordered">
                        <thead>
                        @yield('recent-header-row')
                        </thead>
                        <tbody>
                        @yield('recent-rows')
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@stop