<?php
$addressField = isset($addressField) ? $addressField : 'address';
$addressSettings[$addressField] = [
    'label' => isset($addressLabel) ? $addressLabel : 'Address',
    'required' => $addressRequired = isset($addressRequired) && $addressRequired ? true : false
];
?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group {{ $errors->has($addressField . '.street') ? 'has-error' : '' }}">
            <label class="control-label" for="{{ $addressField }}[street]">{{ $addressSettings[$addressField]['label'] }}</label>
            <input type="text" name="{{ $addressField }}[street]" class="form-control" value="{{ $street }}" placeholder="Street Address"{{ $addressSettings[$addressField]['required'] ? ' required' : '' }} />
            @if ($errors->has($addressField . '.street'))
                <span class="help-block">
                    <strong>{{ $errors->first($addressField . '.street') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        <div class="form-group {{ $errors->has($addressField . '.city') ? 'has-error' : '' }}">
            <input type="text" name="{{ $addressField }}[city]" class="form-control" value="{{ $city }}" placeholder="City"{{ $addressSettings[$addressField]['required'] ? ' required' : '' }} />
            @if ($errors->has($addressField . '.city'))
                <span class="help-block">
                    <strong>{{ $errors->first($addressField . '.city') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group {{ $errors->has($addressField . '.state_id') ? 'has-error' : '' }}">
            <select class="form-control" style="width: 100%;" name="{{ $addressField }}[state_id]" id="{{ $addressField }}-state_id" data-placeholder="State"{{ $addressSettings[$addressField]['required'] ? ' required' : '' }}>
                <option></option>
                @foreach($stateList as $state)
                    <option value="{{ $state['id'] }}"{{ $state_id == $state['id'] ? ' selected' : '' }}>{{ $state['iso'] }}</option>
                @endforeach()
            </select>
            @if ($errors->has($addressField . '.state_id'))
                <span class="help-block">
                    <strong>{{ $errors->first($addressField . '.state_id') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group {{ $errors->has($addressField . '.zip') ? 'has-error' : '' }}">
            <input type="text" name="{{ $addressField }}[zip]" class="form-control" value="{{ $zip }}" placeholder="Zip"{{ $addressSettings[$addressField]['required'] ? ' required' : '' }} />
            @if ($errors->has($addressField . '.zip'))
                <span class="help-block">
                    <strong>{{ $errors->first($addressField . '.zip') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

@push('js')
<script type="text/javascript">
    $('#{{ $addressField }}-state_id').select2();
</script>
@endpush