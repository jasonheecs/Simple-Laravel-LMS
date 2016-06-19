@extends('layouts.master')

@section('layout')
<div class="container container--full-height container--full-width container--nowrap">
    @include('shared.sidebar')

    <main class="content">
        @include('shared.topbar')
        
        <div class="content__wrapper container container--full-width container--centered">
            @include('flash')
            @yield('hero')

            <div class="container container--f-start">
                <div class="flex__item flex__item--3">
                    <div id="alert" class="alert hidden"></div>

                    @yield('content')
                </div>
            </div>
        </div>      
    </main>
</div>
@stop