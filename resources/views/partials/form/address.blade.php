<div class="row">
    <div class="col-sm-12">
        <div class="form-group {{ $errors->has('address.street') ? 'has-error' : '' }}">
            <label class="control-label" for="address.street">Address</label>
            <input type="text" name="address.street" class="form-control" value="{{ $street }}" placeholder="Street Address" />
            @if ($errors->has('address.street'))
                <span class="help-block">
                            <strong>{{ $errors->first('address.street') }}</strong>
                        </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        <div class="form-group {{ $errors->has('address.city') ? 'has-error' : '' }}">
            <input type="text" name="address.city" class="form-control" value="{{ $city }}" placeholder="City" />
            @if ($errors->has('address.city'))
                <span class="help-block">
                    <strong>{{ $errors->first('address.city') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group {{ $errors->has('address.state_id') ? 'has-error' : '' }}">
            <select class="form-control" style="width: 100%;" name="address.state_id" id="address-state_id" data-placeholder="State...">
                <option></option>
                @foreach($stateList as $state)
                    <option value="{{ $state['id'] }}"{{ $state_id == $state['id'] ? ' selected' : '' }}>{{ $state['iso'] }}</option>
                @endforeach()
            </select>
            @if ($errors->has('address.state_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('address.state_id') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group {{ $errors->has('address.zip') ? 'has-error' : '' }}">
            <input type="text" name="address.zip" class="form-control" value="{{ $zip }}"
                   placeholder="Zip" />
            @if ($errors->has('address.zip'))
                <span class="help-block">
                    <strong>{{ $errors->first('address.zip') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

@push('js')
<script type="text/javascript">
    $('#address-state_id').select2();
</script>
@endpush