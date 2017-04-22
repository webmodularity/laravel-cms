<div class="form-group">
    <label class="col-sm-2 control-label">Time</label>
    <div class="col-sm-10">
        <p class="form-control-static">{{ $logUser->created_at->format('m/d/Y h:i:sa') }}</p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Action</label>
    <div class="col-sm-10">
        <p class="form-control-static">{{ $logUser->userAction->slug }}</p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Request Method</label>
    <div class="col-sm-10">
        <p class="form-control-static">{{ $logUser->logRequest->requestMethod->method }}</p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">URL</label>
    <div class="col-sm-10">
        <p class="form-control-static">{{ $logUser->logRequest->urlPath->url_path }}</p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Query String</label>
    <div class="col-sm-10">
        <p class="form-control-static">{!! !is_null($logUser->logRequest->queryString) ? $logUser->logRequest->queryString->query_string : '<em>' . 'None' . '</em>' !!}</p>
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
        <p class="form-control-static">{!! !is_null($logUser->socialProvider) ? $logUser->socialProvider->getName() : '<em>' . 'None' . '</em>' !!}</p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Session ID</label>
    <div class="col-sm-10">
        <p class="form-control-static">{{ $logUser->logRequest->session_id }}</p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">User</label>
    <div class="col-sm-10">
        <p class="form-control-static">{{ $logUser->user->person->email }}</p>
    </div>
</div>