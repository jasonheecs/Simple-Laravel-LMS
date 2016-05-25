@extends('layouts.with-sidebar')

@section('content')
    <div class="panel panel--default">
        <h1 class="panel__heading">{{ $course->title }}</h1>
        <div class="panel__content">
            <ul class="list list--plain">
                @foreach ($course->lessons as $key=>$lesson)
                    <li>
                        <a href="{{ url('/lessons', $lesson->id ) }}">Lesson {{ $key + 1 }} - {{ $lesson->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@stop