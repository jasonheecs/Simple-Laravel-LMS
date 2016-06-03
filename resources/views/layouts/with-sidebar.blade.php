@extends('layouts.master')

@section('layout')
    <div class="container container--full-height container--nowrap">
        @include('layouts.sidebar')

        <main class="content">
            @include('layouts.topbar')
            
            <div class="content__wrapper">
                @include('flash')

                <div class="container container--f-start">
                    <div class="flex__item flex__item--3">
                        <div id="alert" class="alert hidden"></div>

                        @yield('content')
                    </div>

                    <div class="flex__item flex__item--1 hidden">
                        <div class="panel panel--default">

                        </div>
                    </div>
                </div>
            </div>      
        </main>
    </div>
@stop