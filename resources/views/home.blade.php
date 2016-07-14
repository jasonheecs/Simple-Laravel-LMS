@extends('layouts.with-sidebar')

@section('pageName', 'js-home-page')

@section('hero')
    @include('shared.hero', [
                            'hero_image' => 'img/bg/home.jpg',
                            'hero_title' => 'Welcome,',
                            'hero_subtitle' => Auth::user()->name
                            ])
@stop

@section('content')
<div class="container">
    @if (count($instructing))
        <div class="panel panel--secondary">
            <h2 class="panel__title panel__title--small panel__divider">Courses you are Instructing</h2>
            <div class="grid grid--width-1-1 grid--width-medium-1-2 grid--width-large-1-3 grid--small grid--match">
                @foreach ($instructing as $lecturer)
                    @foreach ($lecturer->courses as $course)
                        <div>
                            @include('shared.card-grid-item')
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif

    @if (count($studying))
        <div class="panel panel--secondary">
            <h2 class="panel__title panel__title--small panel__divider">Courses you are Studying</h2>
            <div class="grid grid--width-1-1 grid--width-medium-1-2 grid--width-large-1-3 grid--small grid--match">
                @foreach ($studying as $student)
                    @foreach ($student->courses as $course)
                        <div>
                           @include('shared.card-grid-item')
                       </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
