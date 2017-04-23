<div class="row">
    <div class="col-sm-8">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label class="control-label" for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email',  isset($person->email) ? $person->email : null) }}"
                   placeholder="Email Address" required />
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group {{ $errors->has('role_id') ? 'has-error' : '' }}">
            <label class="control-label" for="role_id">Role</label>
            <select class="form-control" style="width: 100%;" name="role_id" id="role_id" required>
                @foreach($userRoles as $userRole)
                    <option value="{{ $userRole['id'] }}"{{ old('role_id', isset($role_id) ? $role_id : null) == $userRole['id'] ? ' selected' : '' }}>{{ studly_case($userRole['slug']) }}</option>
                @endforeach()
            </select>
            @if ($errors->has('role_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('role_id') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-8 col-sm-5 col-md-8 col-lg-5">
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
            <label class="control-label" for="first_name">Name (Optional)</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name',  isset($person->first_name) ? $person->first_name : null) }}" placeholder="First" />
            @if ($errors->has('first_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-xs-4 col-sm-2 col-md-4 col-lg-2">
        <div class="form-group {{ $errors->has('middle_name') ? 'has-error' : '' }}">
            <label class="control-label hidden-sm hidden-lg" for="middle_name">&nbsp;</label>
            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name',  isset($person->middle_name) ? $person->middle_name : null) }}" placeholder="Middle" />
            @if ($errors->has('middle_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('middle_name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-12 col-lg-5">
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
            <label class="control-label hidden-sm hidden-lg" for="last_name">&nbsp;</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name',  isset($person->last_name) ? $person->last_name : null) }}" placeholder="Last" />
            @if ($errors->has('last_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        @include('wmcms::partials.form.address', ['address' => old('address', isset($primaryAddress) && !is_null($primaryAddress) ? $primaryAddress : null)])
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group {{ $errors->has('phones.mobile') ? 'has-error' : '' }}">
            <label class="control-label" for="phones[mobile]">Phones (Optional)</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-mobile-phone fa-fw"></i>
                </div>
                <input type="tel" name="phones[mobile]" class="form-control" data-inputmask="'mask': '(999) 999-9999'"
                       value="{{ old('phones.mobile', isset($phones) && !is_null($phones['mobile']) ? $phones['mobile']->area_code . $phones['mobile']->number : null) }}" placeholder="Mobile Phone">
            </div>
            @if ($errors->has('phones.mobile'))
                <span class="help-block">
                    <strong>{{ $errors->first('phones.mobile') }}</strong>
                </span>
            @endif
            <!-- /.input group -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group {{ $errors->has('phones.office') ? 'has-error' : '' }}">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-phone fa-fw"></i>
                </div>
                <input type="tel" name="phones[office]" class="form-control" data-inputmask="'mask': '(999) 999-9999 [x9999999]'"
                       value="{{ old('phones.office', isset($phones) && !is_null($phones['office']) ? $phones['office']->area_code . $phones['office']->number . $phones['office']->extension : null) }}" placeholder="Office Phone">
            </div>
            @if ($errors->has('phones.office'))
                <span class="help-block">
                    <strong>{{ $errors->first('phones.office') }}</strong>
                </span>
        @endif
        <!-- /.input group -->
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group {{ $errors->has('phones.fax') ? 'has-error' : '' }}">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-fax fa-fw"></i>
                </div>
                <input type="tel" name="phones[fax]" class="form-control" data-inputmask="'mask': '(999) 999-9999 [x9999999]'"
                       value="{{ old('phones.fax', isset($phones) && !is_null($phones['fax']) ? $phones['fax']->area_code . $phones['fax']->number . $phones['fax']->extension : null) }}" placeholder="Fax">
            </div>
            @if ($errors->has('phones.fax'))
                <span class="help-block">
                    <strong>{{ $errors->first('phones.fax') }}</strong>
                </span>
        @endif
        <!-- /.input group -->
        </div>
    </div>
</div>
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/bindings/inputmask.binding.min.js"></script>
@endpush