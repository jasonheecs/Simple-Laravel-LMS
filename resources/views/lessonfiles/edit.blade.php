@extends('layouts.with-sidebar')

@section('content')
    <div class="panel panel--default">
        <h1 class="panel__heading">Edit Lesson File</h1>

        <div class="panel__content">
            <form method="POST" action="/files/{{ $file->id }}">
                {{ method_field('PATCH') }}
                <div class="form-group">
                    <label for="filename">File Name</label>
                    <input type="text" id="filename" name="filename" class="form-control" placeholder="File Name" value="{{ $file->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">File Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="File Description">{{ $file->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="url">File URL</label>
                    <input type="text" id="url" name="url" class="form-control" placeholder="File URL" value="{{ $file->url }}" required>
                </div>
                <button type="submit" class="btn btn--primary">Save</button>
                {!! csrf_field() !!}
            </form>
        </div>
    </div>
@stop