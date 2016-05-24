@extends('layouts.with-sidebar')

@section('content')
<div class="dashboard">
    <div class="panel panel-default">
        @if (count($instructing))
            @foreach ($instructing as $lecturer)
                @foreach ($lecturer->courses as $course)
                    {{ $course->title }}
                @endforeach
            @endforeach
        @endif
        {{ Auth::user()->is('admin') }}
    </div>

    <div class="panel-default">
        @if (count($studying))
            <h3>Courses you are enrolled in</h3>
            @foreach ($studying as $student)
                @foreach ($student->courses as $course)
                    {{ $course->title }}
                @endforeach
            @endforeach
        @endif
    </div>
</div>
@endsection
