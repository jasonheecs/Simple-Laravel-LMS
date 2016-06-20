@extends('layouts.master')

@section('layout')
@include('shared.sidebar')

<main class="content">
    @include('shared.topbar')
    
    <div>
        @include('flash')
        @yield('hero')

        <div class="container padding--remove">
            <div class="">
                <div id="alert" class="alert hidden"></div>

                @yield('content')
            </div>
        </div>
    </div>      
</main>
@stop