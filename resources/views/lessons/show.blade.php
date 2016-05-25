@extends('layouts.with-sidebar')

@section('content')
    <div class="panel panel--default">
        <h1 class="panel__heading">{{ $lesson->title }}</h1>
        <div class="panel__content">
            {{ $lesson->body }}

            @if (count($lesson->files))
                <div class="lesson-files">
                    <h3 class="lesson-files__header">Lesson Files</h3>
                    <ul class="list--files">
                        @foreach ($lesson->files as $file)
                            <li>
                                <h4 class="lesson-file-name"><a href="{{ $file->url }}">{{ $file->name }}</a></h4>
                                <p class="lesson-file-desc">{{ $file->description }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@stop