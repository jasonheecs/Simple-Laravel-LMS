@extends('layouts.app')

@section('content')
    <h1>All Courses</h1>

    <ul>
    @foreach ($courses as $course)
        <li>
            <a href="/courses/{{ $course->id }}">{{ $course->title }}</a>
        </li>
    @endforeach
    </ul>
@stop