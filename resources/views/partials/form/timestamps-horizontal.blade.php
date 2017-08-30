<div class="form-group">
    <label class="col-sm-2 control-label">Created At</label>
    <div class="col-sm-10">
        <p class="form-control-static">
            {{ $created_at->format('m/d/Y h:i:sa') }}
        </p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Updated At</label>
    <div class="col-sm-10">
        <p class="form-control-static">
            {{ $updated_at->format('m/d/Y h:i:sa') }}
        </p>
    </div>
</div>