@extends('layouts.no-sidebar')

@section('content')
<div class="login">
    <div id="login-box" class="login-box">
        <div class="login__container">
            {{-- <img src="{{ asset('img/logo.png') }}" /> --}}
            <h1>Demo</h1>
            <h1 class="login__site-name">Learning Management System</h1>
            <form class="login-form" role="form" method="POST" action="{{ url('/login') }}">
                <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="login-form__input login-form__input--name" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}"/>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="login-form__input login-form__input--password" name="password" id="password" placeholder="Password" />

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="login-remember">
                    <input type="checkbox" name="remember" id="remember" />
                    <label class="login-remember__label" for="remember">Keep me logged in</label>
                </div>
                <div class="login-btn__container">
                    <button class="btn btn--lg btn--login">
                        Log In
                    </button>
                </div>
                {!! csrf_field() !!}
            </form>
        </div>
        <div class="login-footer">
            <a href="{{ url('/password/reset') }}" class="login-footer__link">
                Forgot your password?
            </a>
        </div>
    </div>
</div>
@endsection
