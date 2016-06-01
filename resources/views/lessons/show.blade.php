@extends('layouts.with-sidebar')

@section('head')
    @if (Auth::user()->canEdit($lesson->course))
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endif
@stop

@section('content')
    <div id="alert" class="alert hidden"></div>

    <div id="lesson-panel" class="panel panel--default">
        <h1 id="lesson-title-content" class="panel__heading title-editable">{{ $lesson->title }}</h1>
        <div class="panel__content">
            @if (Auth::user()->canEdit($lesson->course))
                <input type="hidden" name="lesson-id" id="lesson-id" value="{{ $lesson->id }}">
            @endif

            <div id="lesson-body-content" class="body-editable">
                {!! $lesson->body !!}
            </div>

            @if (count($lesson->files))
                <div class="lesson-files">
                    <h3 class="lesson-files__header">Lesson Files</h3>
                    <ul class="list list--files list--striped">
                        @foreach ($lesson->files as $file)
                            <li>
                                <h4 class="lesson-file-name">
                                    <a class="lesson-file-name__link" href="{{ $file->url }}">{{ $file->name }}</a>
                                </h4>
                                <p class="lesson-file-desc">{{ $file->description }}</p>

                                @if (Auth::user()->canEdit($lesson->course))
                                    <ul class="list list--inline lesson-files-actions">
                                        <li><a class="btn btn--flat btn--sm" href="/files/{{ $file->id }}/edit">Edit</a></li>
                                        <li>
                                            <form method="POST" action="{{ url('/files', $file->id) }}">
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class='btn btn--flat-danger btn--sm'>Delete</button>
                                                {!! csrf_field() !!}
                                            </form>
                                        </li>
                                    </ul>
                                @endif

                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="panel__divider">
                <a href="{{ url('/courses', $lesson->course->id) }}" class="lesson-nav lesson-nav--back">
                    Back to all lessons
                </a>
                <a href="" class="lesson-nav lesson-nav--next">
                    Next Lesson
                </a>
            </div>

            @if (Auth::user()->canEdit($lesson->course))
                <ul id="lesson-admin-actions" class="list list--inline button-group button-group--right margin--top admin-actions-group">
                    <li>
                        <a id="edit-lesson-btn" class="btn btn--primary">Edit Lesson</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ url('/lessons', $lesson->id) }}">
                            {{ method_field('DELETE') }}
                            <button id="delete-lesson-btn" type="submit" class="btn btn--danger">Delete</button>
                            {!! csrf_field() !!}
                        </form>
                    </li>
                    <li>
                        @if ($lesson->published)
                            <a id="unpublish-lesson-btn" class="btn btn--muted btn-lesson-publish">Unpublish</a>
                        @else
                            <a id="publish-lesson-btn" class="btn btn--muted-inverse btn-lesson-publish">Publish</a>
                        @endif
                    </li>
                    <li>
                        <a id="add-lesson-file-btn" class="btn btn--outline">Add Lesson File</a>
                    </li>
                </ul>
                <ul id="lesson-content-actions" class="hidden list list--inline button-group button-group--right margin--top admin-actions-group">
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

    @if (Auth::user()->canEdit($lesson->course))
    {{-- Add lesson file HTML template used by JS --}}
    <script id="lesson-hidden-template" type="text/x-custom-template">
        <div class="panel panel--default ">
            <h2 class="panel__heading">Add Lesson File</h2>
            <div class="form-group">
                <label for="filename">File Name</label>
                <input type="text" id="filename" name="filename" class="form-control" placeholder="File Name" value="{{ old('filename') }}" required>
            </div>
            <div class="form-group">
                <label for="description">File Description</label>
                <textarea id="description" name="description" class="form-control form-control--textarea" rows="4" placeholder="File Description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="url">File URL</label>
                <input type="text" id="url" name="url" class="form-control" placeholder="File URL" value="{{ old('url') }}" required>
            </div>
            <button type="submit" class="btn btn--primary margin--top float--right">Save</button>
        </div>
    </script>

    <form id="add-lesson-file-form" class="add-file-form hidden" method="POST" action="/lessons/{{ $lesson->id }}/files">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! csrf_field() !!}
    </form>
    @endif
@stop