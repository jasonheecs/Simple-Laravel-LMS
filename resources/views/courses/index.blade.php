@extends('layouts.with-sidebar')

@section('hero')
    @include('shared.hero', ['hero_image' => 'img/bg/all_courses.jpg'])
@stop

@section('content')
<div class="panel items-grid">
    <div class="panel__content">
        <ul class="list list--plain">
            @foreach ($courses as $course)
                <li>
                    <a href="/courses/{{ $course->id }}">{{ $course->title }}</a>
                </li>
            @endforeach
        </ul>

        @if (Auth::user()->canCreateCourse())
            <ul id="course-admin-actions" class="list list--inline button-group button-group--right margin--top admin-actions-group">
                <li>
                    <form method="GET" action="{{ url('/courses/create') }}">
                        <button id="create-course-btn" class="btn btn--primary" type="submit">Create Course</button>
                    </form>
                </li>
            </ul>
        @endif
    </div>
</div>
@stop