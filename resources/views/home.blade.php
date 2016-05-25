@extends('layouts.with-sidebar')

@section('content')
    @if (count($instructing))
        <div class="panel panel--default">
            <h1 class="panel__heading">Courses you are instructing</h1>
            <div class="panel__content">
                <ul class="list--plain">
                    @foreach ($instructing as $lecturer)
                        @foreach ($lecturer->courses as $course)
                            <li><a href="{{ url('/courses', $course->id) }}">{{ $course->title }}</a></li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (count($studying))
        <div class="panel panel--default">
            <h1 class="panel__heading">Courses you are enrolled in</h1>
            <div class="panel__content">
                <ul class="courses-list">
                    @foreach ($studying as $student)
                        @foreach ($student->courses as $course)
                            <li><a href="{{ url('/courses', $course->id) }}">{{ $course->title }}</a></li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endsection
