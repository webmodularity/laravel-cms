<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{!! $boxTitle !!}</h3>
        <div class="box-tools pull-right">
            @yield('box-tools')
            <label class="label label-primary">ID: {{ $recordId }}</label>
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
            <button type="button" class="btn btn-danger pull-left" id="record-delete-button"><i class="fa fa-times"></i>&nbsp;Delete</button>
            @hasSection('editUpdateButton')
                @yield('editUpdateButton')
            @else
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            @endif
        </div>

        <div class="box-footer">


        </div>
    </form>
</div>