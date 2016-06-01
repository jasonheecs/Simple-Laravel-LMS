@extends('layouts.with-sidebar')

@section('content')
<form method="POST" action="{{ url('/lessons') }}">
    <div id="lesson-panel" class="panel panel--default">
        <input type="hidden" id="create-lesson" value="true" />
        <input type="hidden" id="lesson-title" name="title" class="form-control create-lesson__title" placeholder="Lesson Title" value="{{ old('title') }}" />
        <h1 id="lesson-title-content" class="panel__heading title-editable">{{ old('title') }}</h1>
        <div class="panel__content">
            <textarea id="lesson-body-content" name="body" class="body-editable" required>
                {{ old('body') }}
            </textarea>
        
            <ul id="lesson-content-actions" class="list list--inline button-group button-group--right margin--top admin-actions-group">
                <input type="hidden" name="course_id" value="{{ $course_id }}">
                <li>
                    <button type="submit" class="btn btn--primary" name="save" value="true">Save</button>
                </li>
                <li>
                    <button type="submit" class="btn btn--default" name="cancel" value="true">Cancel</button>
                </li>
                {!! csrf_field() !!}
            </ul>
        </div>
    </div>
</form>
@stop