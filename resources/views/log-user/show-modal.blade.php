@section('userLogForm')
    <div class="form-group">
        <label class="col-sm-2 control-label">Time</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogCreatedAt"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Action</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUserAction"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Request Method</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogRequestMethod"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">URL</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUrlPath"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Query String</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogQueryString" style="-ms-word-break: break-all; word-break: break-all;"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Ajax Request</label>
        <div class="col-sm-10">
            <p class="form-control-static"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Social Provider</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogSocialProvider"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Session ID</label>
        <div class="col-sm-10">
            <p class="form-control-static" style="-ms-word-break: break-all; word-break: break-all; word-break: break-word;"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">IP Address</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogIpAddress"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">User</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUser"></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">User Agent</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUserAgent" style="-ms-word-break: break-all; word-break: break-all; word-break: break-word;"></p>
        </div>
    </div>
@endsection

@include('wmcms::crud.show-modal', [
    'showModalId' => 'userLog',
    'showModalHeader' => 'User Log Details'
])