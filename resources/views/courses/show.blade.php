@extends('layouts.with-sidebar')

@section('head')
    @if (Auth::user()->canEdit($course))
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endif
@stop

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

            @if (Auth::user()->canEdit($course))
                <ul id="course-admin-actions" class="course-admin-actions list list--inline">
                    <li>
                        <a id="edit-course-btn" class="btn btn--primary">Edit Course</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ url('/courses', $course->id) }}">
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn--alert">Delete Course</button>
                            {!! csrf_field() !!}
                        </form>
                    </li>
                    <li>
                        <a class="btn btn--secondary" href="{{ url('/lessons/create') }}">Add Lesson</a>
                    </li>
                </ul>
                <ul id="course-content-actions" class="hidden course-admin-actions list list--inline">
                    <li>
                        <a id="save-changes-btn" class="btn btn--primary">Save Changes</a>
                    </li>
                    <li>
                        <a id="cancel-changes-btn" class="btn btn--muted">Cancel</a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
@stop