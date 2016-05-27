@extends('layouts.with-sidebar')

@section('head')
    @if (Auth::user()->canEdit($lesson->course))
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @endif
@stop

@section('content')
    <div id="alert" class="alert"></div>

    <div id="lesson-panel" class="panel panel--default">
        <h1 id="lesson-title-content" class="panel__heading title-editable">{{ $lesson->title }}</h1>
        <div class="panel__content">
            @if (Auth::user()->canEdit($lesson->course))
                <input type="hidden" name="lesson-id" id="lesson-id" value="{{ $lesson->id }}">
            @endif

            <article id="lesson-body-content" class="body-editable">
                {!! $lesson->body !!}
            </article>

            @if (count($lesson->files))
                <div class="lesson-files">
                    <h3 class="lesson-files__header">Lesson Files</h3>
                    <ul class="list list--files">
                        @foreach ($lesson->files as $file)
                            <li>
                                <h4 class="lesson-file-name">
                                    <a href="{{ $file->url }}">{{ $file->name }}</a>

                                    @if (Auth::user()->canEdit($lesson->course))
                                        <div>
                                            <ul class="list list--inline">
                                                <li><a href="/files/{{ $file->id }}/edit">Edit</a></li>
                                                <li>
                                                    <form method="POST" action="{{ url('/files', $file->id) }}">
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit">Delete</button>
                                                        {!! csrf_field() !!}
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                </h4>
                                <p class="lesson-file-desc">{{ $file->description }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Auth::user()->canEdit($lesson->course))
                <ul id="lesson-admin-actions" class="lesson-admin-actions list list--inline">
                    <li>
                        <a id="edit-lesson-btn" class="btn btn--primary">Edit Lesson</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ url('/lessons', $lesson->id) }}">
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn--alert">Delete</button>
                            {!! csrf_field() !!}
                        </form>
                    </li>
                    <li>
                        <a class="btn btn--outline">Add Lesson File</a>
                    </li>
                </ul>
                <ul id="lesson-content-actions" class="hidden lesson-admin-actions list list--inline">
                    <li>
                        <a id="save-changes-btn" class="btn btn--primary">Save Changes</a>
                    </li>
                    <li>
                        <a id="cancel-changes-btn" class="btn btn--muted">Cancel</a>
                    </li>
                </ul>
            @endif
        </div>
    </div>

    @if (Auth::user()->canEdit($lesson->course))
    <form class="add-file-form" method="POST" action="/lessons/{{ $lesson->id }}/files">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel panel--default">
            <h2 class="panel__heading">Add Lesson File</h2>
            <div class="form-group">
                <label for="filename">File Name</label>
                <input type="text" id="filename" name="filename" class="form-control" placeholder="File Name" value="{{ old('filename') }}" required>
            </div>
            <div class="form-group">
                <label for="description">File Description</label>
                <textarea id="description" name="description" class="form-control" rows="3" placeholder="File Description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="url">File URL</label>
                <input type="text" id="url" name="url" class="form-control" placeholder="File URL" value="{{ old('url') }}" required>
            </div>
            <button type="submit" class="btn btn--primary">Save</button>
        </div>
        {!! csrf_field() !!}
    </form>
    @endif
@stop