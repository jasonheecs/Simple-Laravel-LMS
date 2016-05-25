@extends('layouts.with-sidebar')

@section('content')
    <div class="panel panel--default">
        <h1 class="panel__heading">{{ $lesson->title }}</h1>
        <div class="panel__content">
            {{ $lesson->body }}

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
                                                <li><a href="#">Edit</a></li>
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
                <ul class="admin-actions list list--inline">
                    <li>
                        <a class="btn btn--primary">Edit Lesson</a>
                    </li>
                    <li>
                        <a class="btn btn--alert">Delete Lesson</a>
                    </li>
                    <li>
                        <a class="btn btn--secondary">Add Lesson File</a>
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