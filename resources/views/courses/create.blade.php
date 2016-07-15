@extends('layouts.with-sidebar')

@section('pageName', 'js-create-course-page')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@stop

@section('hero')
    @include('shared.hero', [
        'sub_template' => 'courses.hero-sub'
    ])
@stop

@section('content')
<div class="panel panel--default">
    <form method="POST" action="{{ url('/courses') }}">
        <input type="hidden" id="course-img" name="image" value="">

        <div class="form-group">
            <label for="course-title">Enter course title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter course title" autofocus required />
        </div>
        <button id="save-changes-btn" class="btn btn--primary btn--md" type="submit" name="save" value="save"><i class="icon icon--save"></i> Save</button>
        <button id="cancel-changes-btn" class="btn btn--outline btn--md" type="submit" name="cancel" value="cancel" formnovalidate><i class="icon icon--cancel"></i> Cancel</button>
        {!! csrf_field() !!}
    </form>
</div>
@stop