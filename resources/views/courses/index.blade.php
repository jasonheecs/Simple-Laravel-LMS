@extends('layouts.with-sidebar')

@section('pageName', 'js-courses-page')

@section('hero')
    @include('shared.hero', [
        'hero_image' => 'img/bg/all_courses.jpg',
        'hero_title' => 'All Courses',
        'hero_subtitle' => 'A directory of useful courses'
    ])
@stop

@section('content')
<div class="container">
    <div id="courses-panel" class="panel panel--secondary">
        <div class="courses__header flex flex--space-between margin--bottom">
            <div>@include('svg.courses_black'){{ count($courses) }} Courses</div>
            @can('store', App\Course::class)
                <ul id="course-admin-actions" class="list list--inline button-group button-group--right">
                    <li>
                        <form method="GET" action="{{ url('/courses/create') }}">
                            <button id="create-course-btn" class="btn btn--primary" type="submit"><i class="icon icon--create-course"></i> Create Course</button>
                        </form>
                    </li>
                </ul>
            @endcan
        </div>

        <div class="grid grid--width-1-1 grid--width-medium-1-2 grid--width-large-1-3 grid--small grid--match">
            @foreach ($courses as $course)
                <div>
                    @include('shared.card-grid-item')
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop