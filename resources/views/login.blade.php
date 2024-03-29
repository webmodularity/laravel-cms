@extends('wmcms::master')

@section('body_class', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
            @foreach(['danger', 'success', 'warning', 'info'] as $calloutType)
                @if(session()->has($calloutType))
                    <div class="callout callout-{{ $calloutType }}">
                        <p>{{ session($calloutType) }}</p>
                    </div>
                @endif
            @endforeach
            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                {!! csrf_field() !!}

                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="fa fa-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="fa fa-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        @if(config('wm.user.remember'))
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        @endif
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit"
                                class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <div class="auth-links">
                <a href="{{ url('password/reset') }}" class="text-center">Forgot password?</a>
                @if (config('wm.user.register', false))
                    <br />
                    <a href="{{ url('register') }}" class="text-center">Register new user account.</a>
                @endif
            </div>
            <!-- Social Logins -->
            @if(config('wm.user.social'))
                <div class="social-auth-links text-center">
                    <p class="text-center">- OR -</p>
                    @foreach($socialProviders as $socialProvider)
                        <a href="social/{{ $socialProvider->slug }}" class="btn btn-block btn-social btn-{{ $socialProvider->slug }}">
                            <i class="fa fa-{{ $socialProvider->slug }}"></i> Sign in with {{ $socialProvider->getName() }}
                        </a>
                    @endforeach
                </div>
            @endif()
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@push('js')
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endpush