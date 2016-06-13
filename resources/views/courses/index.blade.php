@extends('layouts.with-sidebar')

@section('hero')
    @include('shared.hero', [
                            'hero_image' => 'img/bg/all_courses.jpg',
                            'hero_title' => 'All Courses',
                            'hero_subtitle' => 'A directory of useful courses'
                            ])
@stop

@section('content')
<div class="container">
    <div class="items-grid items-grid--3 items-grid--first">
        <div class="panel items-grid-panel">
            @foreach ($courses as $course)
                @include('shared.card-grid-item')
            @endforeach
        </div>

        @if (Auth::user()->canCreateCourse())
        <div class="flex__item flex__item--full-width ">
            <ul id="course-admin-actions" class="list list--inline button-group button-group--right margin--top admin-actions-group">
                <li>
                    <form method="GET" action="{{ url('/courses/create') }}">
                        <button id="create-course-btn" class="btn btn--primary" type="submit">Create Course</button>
                    </form>
                </li>
            </ul>
        </div>
        @endif
    </div>
</div>

@stop