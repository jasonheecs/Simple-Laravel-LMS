@extends('layouts.app')

@section('content')
    {{ $course }}
    <h1>{{ $course->title }}</h1>

    <ul>
    @foreach ($course->lessons as $lesson)
        <li>
            <a href="/lessons/{{ $lesson->id }}">{{ $lesson->title }}</a>
        </li>
    @endforeach
    </ul>
@stop