@extends('layouts.with-sidebar')

@section('content')
    <div id="lesson-panel" class="panel panel--default">
        <h1 id="lesson-title-content" class="panel__heading title-editable">{{ old('title') }}</h1>
        <div class="panel__content">
            <article id="lesson-body-content" class="body-editable">
                {{ old('body') }}
            </article>

            <ul id="lesson-content-actions" class="lesson-admin-actions list list--inline">
                <li>
                    <a id="save-changes-btn" class="btn btn--primary">Save Changes</a>
                </li>
                <li>
                    <a id="cancel-changes-btn" class="btn btn--muted">Cancel</a>
                </li>
            </ul>
        </div>
    </div>
@stop