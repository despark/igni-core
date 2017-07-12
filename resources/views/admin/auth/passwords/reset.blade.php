@extends('ignicms::admin.auth.auth')

@section('pageTitle', 'Password Reset')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ asset(config('ignicms.logo')) }}" class="admin-logo" alt="Logo"/>
            <h4 class="uppercase">Website Administration</h4>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="login-box-body">
            <form action="{{ url('/admin/password/reset') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="email" required/>
                    @if ($errors->has('email'))
                        <span class="error-message">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" required/>
                    @if ($errors->has('password'))
                        <span class="error-message">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required/>
                    @if ($errors->has('password_confirmation'))
                        <span class="error-message">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat uppercase">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
