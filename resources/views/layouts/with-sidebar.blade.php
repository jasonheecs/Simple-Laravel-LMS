@extends('layouts.master')

@section('layout')
    <div class="container container--full-height container--nowrap">
        @include('layouts.sidebar')

        <main class="content">
            @include('layouts.topbar')
            
            <div class="content__wrapper">
                @include('flash')

                @yield('content')
            </div>      
        </main>
    </div>
@stop