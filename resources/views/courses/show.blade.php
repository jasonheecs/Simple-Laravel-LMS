@extends('layouts.with-sidebar')

@section('head')
    @if (Auth::user()->canEdit($course))
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endif
@stop

@section('content')
    <div class="container container--f-start">
        <div class="flex__item flex__item--3">
            <div class="panel panel--default">
                <h1 class="panel__heading">{{ $course->title }}</h1>
                <div class="panel__content">
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
        </div>
        <div class="flex__item flex__item--1">
            <div class="panel panel--default">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti repudiandae dolores dolor ullam quae provident quas tempore, eos unde voluptatum, quisquam quidem vero odio animi! Deleniti ea quae quaerat, cum eos placeat inventore ipsum repudiandae, consectetur aliquam esse, voluptates neque ut. Enim perferendis facere, fugiat excepturi temporibus, quia iusto. Distinctio magnam, fuga, laudantium blanditiis enim fugiat ea veritatis. Sequi ipsam laboriosam distinctio ullam repellendus, libero, adipisci alias sed placeat tenetur tempora possimus iure explicabo aliquid sint inventore qui delectus asperiores nam at officiis nisi velit reprehenderit facere. Quam unde illum ipsum quisquam assumenda, consectetur iure fugit? Animi ipsa, itaque eos praesentium earum ipsam sunt! Dignissimos optio, suscipit fugit et saepe laudantium deleniti dicta omnis sequi neque ea accusantium eum blanditiis quos velit! Aliquam harum itaque et voluptate, tempora ad dolor laboriosam voluptas magnam, quae necessitatibus totam incidunt veniam aspernatur. Libero dolor non, maiores quis voluptatibus mollitia incidunt, esse eum repellendus nihil. Saepe aut architecto est nihil eius illum hic ullam eum quo ea, sunt cupiditate perspiciatis reprehenderit beatae quod, magnam labore unde perferendis alias voluptatum similique dignissimos incidunt nulla nostrum. Beatae, vero impedit numquam ipsa similique repudiandae. Voluptate unde dignissimos ea voluptatem natus veritatis amet. Nisi laudantium animi facilis deserunt.
            </div>
        </div>
@stop