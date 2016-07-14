@extends('layouts.with-sidebar')

@section('pageName', 'js-create-course-page')

@section('hero')
    @include('shared.hero', [
        'sub_template' => 'courses.hero-sub'
    ])
@stop

@section('content')
<div class="panel panel--default">
    <form method="POST" action="{{ url('/courses') }}">
        <div class="form-group">
            <label for="course-title">Enter course title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter course title" autofocus required />
        </div>
        <button type="submit" class="btn btn--primary">Save</button>
        {!! csrf_field() !!}
    </form>
</div>
@stop