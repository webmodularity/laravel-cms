@section('userLogForm')
    <div class="form-group">
        <label class="col-sm-2 control-label">Time</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogCreatedAt">{{ $logUser->created_at->format('m/d/Y h:i:sa') }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Action</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUserAction">{{ $logUser->userAction->slug }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Request Method</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogRequestMethod">{{ $logUser->logRequest->requestMethod->method }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">URL</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUrlPath">{{ $logUser->logRequest->urlPath->url_path }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Query String</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogQueryString" style="-ms-word-break: break-all; word-break: break-all;">{!! !is_null($logUser->logRequest->queryString) ? $logUser->logRequest->queryString->query_string : '<em>' . 'None' . '</em>' !!}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Ajax Request</label>
        <div class="col-sm-10">
            <p class="form-control-static">{{ $logUser->logRequest->is_ajax ? 'Yes' : 'No' }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Social Provider</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogSocialProvider">{!! !is_null($logUser->socialProvider) ? $logUser->socialProvider->getName() : '<em>' . 'None' . '</em>' !!}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Session ID</label>
        <div class="col-sm-10">
            <p class="form-control-static" style="-ms-word-break: break-all; word-break: break-all; word-break: break-word;">{{ $logUser->logRequest->session_id }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">IP Address</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogIpAddress">{!! !is_null($logUser->logRequest->ipAddress) ? $logUser->logRequest->ipAddress->ip : '<em>' . 'None' . '</em>' !!}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">User</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUser">{{ $logUser->user->person->email }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">User Agent</label>
        <div class="col-sm-10">
            <p class="form-control-static" id="userLogUserAgent" style="-ms-word-break: break-all; word-break: break-all; word-break: break-word;">{{ $logUser->logRequest->userAgent->user_agent }}</p>
        </div>
    </div>
@endsection

@include('wmcms::crud.show-modal', [
    'showModalId' => 'userLog',
    'showModalHeader' => 'User Log Details'
])