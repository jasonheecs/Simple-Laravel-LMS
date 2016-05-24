<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EFusion Technology LMS - @yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link href='//fonts.googleapis.com/css?family=Roboto:400,500,400italic,700,900,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body id="app-layout">

    <main class="container">
        @yield('layout')
    </main>

    <!-- JavaScripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {{--<script src="{{ elixir('js/app.js') }}"></script>--}}
</body>
</html>
