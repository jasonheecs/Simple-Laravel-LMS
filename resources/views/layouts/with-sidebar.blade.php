@extends('layouts.master')

@section('layout')
    <div class="container">
        @include('layouts.sidebar')

        <main class="content">
            @include('layouts.topbar')
            
            <div class="content__wrapper">
                @yield('content')
            </div>      
        </main>
    </div>
@stop