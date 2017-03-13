<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label">Created At</label>
            <p class="form-control-static">{{ $created_at->format('m/d/Y h:i:sa') }}</p>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label">Updated At</label>
            <p class="form-control-static">{{ $updated_at->format('m/d/Y h:i:sa') }}</p>
        </div>
    </div>
</div>