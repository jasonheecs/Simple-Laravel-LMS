@extends('layouts.with-sidebar')

@section('hero')
    @include('shared.hero', [
                            'hero_image' => 'img/bg/home.jpg',
                            'hero_title' => 'Welcome,',
                            'hero_subtitle' => Auth::user()->name
                            ])
@stop

@section('content')
@if (count($instructing))
<div class="container">
    <div class="items-grid items-grid--3 items-grid--first">
        <div class="panel items-grid-panel">
            <h1 class="items-grid__heading">Courses you are instructing</h1>
            @foreach ($instructing as $lecturer)
                @foreach ($lecturer->courses as $course)
                    <div class="card grid-item">
                        <a href="{{ url('/courses', $course->id) }}">
                            @if (isset($course->image))
                                <figure class="card-figure">
                                    <img src="{{ $course->image }}" />
                                </figure>
                            @endif
                            
                            <h3 class="card-title">{{ $course->title }}</h3>
                        </a>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@endif

@if (count($studying))
<div class="container">
    <div class="items-grid items-grid--3">
        <div class="panel items-grid-panel">
            <h1 class="items-grid__heading">Courses you are Studying</h1>
            @foreach ($studying as $student)
                @foreach ($student->courses as $course)
                    <div class="card grid-item">
                        <a href="{{ url('/courses', $course->id) }}">
                            @if (isset($course->image))
                                <figure class="card-figure">
                                    <img src="{{ $course->image }}" />
                                </figure>
                            @endif
                            
                            <h3 class="card-title">{{ $course->title }}</h3>
                        </a>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
