@extends('layouts.with-sidebar')

@section('content')
<form method="POST" action="{{ url('/lessons') }}">
    <div id="lesson-panel" class="panel panel--default">
        <input type="hidden" id="create-lesson" value="true" />
        <input type="text" id="lesson-title" name="title" class="form-control create-lesson__title" placeholder="Lesson Title" value="{{ old('title') }}" required autofocus />
        <div class="panel__content">
            <textarea id="lesson-body-content" name="body" class="body-editable" required>
                {{ old('body') }}
            </textarea>
        
            <ul id="lesson-content-actions" class="lesson-admin-actions list list--inline">
                <input type="hidden" name="course_id" value="{{ $course_id }}">
                <li>
                    <button type="submit" class="btn btn--primary" name="save" value="true">Save</button>
                </li>
                <li>
                    <button type="submit" class="btn btn--muted" name="cancel" value="true">Cancel</button>
                </li>
                {!! csrf_field() !!}
            </ul>
        </div>
    </div>
</form>
@stop