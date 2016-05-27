@extends('layouts.with-sidebar')

@section('content')
    <div class="panel panel--default">
        <h1 class="panel__heading">All Courses</h1>
        <div class="panel__content">
            <ul class="list list--plain">
                @foreach ($courses as $course)
                    <li>
                        <a href="/courses/{{ $course->id }}">{{ $course->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@stop