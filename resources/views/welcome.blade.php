<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EFusion Technology LMS</title>

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,500,400italic,700,900,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.min.css">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body id="app-layout" class="login">

                <!-- Right Side Of Navbar -->
                {{--<ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul> --}}

    <div id="login-box" class="login-box">
        <div class="login__container">
            <img src="{{ asset('img/efusion-logo.png') }}" alt="" />
            <h1 class="login__site-name">Learning Management System</h1>
            <form class="login-form">
                <input type="text" class="login-form__input login-form__input--name" name="userLogin" id="userLogin" placeholder="Enter your email" />
                <input type="text" class="login-form__input login-form__input--password" name="password" id="password" placeholder="Password" />
                <div class="login-remember">
                    <input type="checkbox" name="rememberMe" id="rememberMe" />
                    <label class="login-remember__label" for="rememberMe">Keep me logged in</label>
                </div>
                <div class="login-btn__container">
                    <button class="btn btn--lg btn--login">
                        Log In
                    </button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
        <div class="login-footer">
            <a href="#" class="login-footer__link">
                Forgot your password?
            </a>
        </div>
    </div>
    <!-- JavaScripts -->
    <script src='js/app.js'></script>
    {{--<script src="{{ elixir('js/app.js') }}"></script>--}}
</body>
</html>
