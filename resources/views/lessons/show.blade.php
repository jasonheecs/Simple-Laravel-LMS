@extends('layouts.app');

@section('content')
    {{ $lesson }}
    <h1>{{ $lesson->title }}</h1>
    <div>{{ $lesson->body }}</div>
@stop