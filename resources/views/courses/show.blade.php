@extends('layouts.with-sidebar')

@section('head')
    @if (Auth::user()->canEdit($course))
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endif
@stop

@section('hero')
    @include('shared.hero', ['hero_image' => $course->image, 'sub_template' => 'courses.hero-sub'])
@stop

@section('content')
<div id="course-panel" class="panel panel--default panel--first">
    <h1 id="course-title-content" class="panel__heading title-editable">{{ $course->title }}</h1>
    <div class="panel__content">
        @if (Auth::user()->canEdit($course))
            <input type="hidden" name="course-id" id="course-id" value="{{ $course->id }}">
        @endif

        <ul class="list list--plain">
            @foreach ($course->lessons as $key=>$lesson)
                <li>
                    @if ($lesson->published || Auth::user()->canEdit($course))
                        <a href="{{ url('/lessons', $lesson->id ) }}">Lesson {{ $key + 1 }} - {{ $lesson->title }}</a>
                    @else
                        Lesson {{ $key + 1 }} - {{ $lesson->title }}
                    @endif
                </li>
            @endforeach
        </ul>

        @if (Auth::user()->canEdit($course))
            <ul id="course-admin-actions" class="list list--inline button-group button-group--right margin--top admin-actions-group">
                <li>
                    <a id="edit-course-btn" class="btn btn--primary">Edit Course</a>
                </li>
                <li>
                    <form method="POST" action="{{ url('/courses', $course->id) }}">
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn--danger">Delete Course</button>
                        {!! csrf_field() !!}
                    </form>
                </li>
                <li>
                    <form method="GET" action="{{ url('/lessons/create') }}">
                        <button class="btn btn--outline" type="submit">Add Lesson</button>
                        <input name="course_id" type="hidden" value="{{ $course->id }}" />
                    </form>
                </li>
            </ul>
            <ul id="course-content-actions" class="hidden list list--inline button-group button-group--right margin--top admin-actions-group">
                <li>
                    <a id="save-changes-btn" class="btn btn--primary">Save Changes</a>
                </li>
                <li>
                    <a id="cancel-changes-btn" class="btn btn--default">Cancel</a>
                </li>
            </ul>
        @endif
    </div>
</div>

@can ('update', $course)
    <div id="lecturers-list" class="panel panel--default hidden">
        <h1 class="panel__heading">Set the following users to be Lecturers in this course</h1>
        <ul class="list">
            <form id="lecturers-form">
                @foreach ($users as $user)
                    <li>
                        @if ($user->isLecturerIn($course))
                            <input type="checkbox" name="{{ $user->id }}" id="lecturer_{{ $user->id }}" checked="true" />
                        @else
                            <input type="checkbox" name="{{ $user->id }}" id="lecturer_{{ $user->id }}" />    
                        @endif
                        <label for="lecturer_{{ $user->id }}">{{ $user->name }}</label>
                    </li>
                @endforeach
            </form>
        </ul>
    </div>
@endcan

@if (Auth::user()->canEdit($course))
    <div id="students-list" class="panel panel--default hidden">
        <h1 class="panel__heading">Set the following users to be Students in this course</h1>
        <ul class="list">
            <form id="students-form">
                @foreach ($users as $user)
                    <li>
                        @if ($user->isStudentIn($course))
                            <input type="checkbox" name="{{ $user->id }}" id="student_{{ $user->id }}" checked="true" />
                        @else
                            <input type="checkbox" name="{{ $user->id }}" id="student_{{ $user->id }}" />    
                        @endif
                        <label for="student_{{ $user->id }}">{{ $user->name }}</label>
                    </li>
                @endforeach
            </form>
        </ul>
    </div>
@endif
@stop