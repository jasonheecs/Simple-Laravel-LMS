@extends('layouts.master')

@section('layout')
@include('shared.sidebar')

<main class="content">
    @include('shared.topbar')
    
    <div>
        @include('flash')
        @yield('hero')

        <div class="container padding--remove">
            <div>
                <ul id="notifications" class="notifications"></ul>

                @yield('content')
            </div>
        </div>
    </div>      
</main>
@stop