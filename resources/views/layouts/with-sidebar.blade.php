@extends('layouts.master')

@section('layout')
    <main class="container">
        @include('layouts.sidebar')
        
        @yield('content')
    </main>
@stop