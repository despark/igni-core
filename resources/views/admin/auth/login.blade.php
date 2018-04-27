@extends('ignicms::admin.auth.auth')

@section('pageTitle', 'Log in')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ asset(config('ignicms.logo')) }}" class="admin-logo" alt="Logo" width="117" />
            <h4 class="uppercase">Website Administration</h4>
        </div>

        <div class="login-box-body">
            <form action="{{ url('/admin/login') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="email"/>
                    @if ($errors->has('email'))
                        <span class="error-message">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password"/>
                    @if ($errors->has('password'))
                        <span class="error-message">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-12 text-center">
                        <button type="submit" class="btn btn-primary btn-block btn-flat uppercase">Sign In</button>
                        <br>
                        <a href="{{ url('/admin/password/reset') }}">Forgot your password?</a>
                    </div>
                </div>

                <div class="text-center">
                    <input type="checkbox" name="remember" id="remember" class="css-checkbox">
                    <label for="remember" class="css-label">Remember me</label>
                </div>
            </form>
        </div>
    </div>
@endsection
