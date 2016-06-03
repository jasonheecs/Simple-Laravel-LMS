@extends('layouts.with-sidebar')

@section('content')
    <div class="panel panel--default">
        <h1 class="panel__heading">All Users</h1>
        <div class="panel__content">
            <ul class="list list--plain">
                @foreach ($users as $user)
                    <li>
                        <a href="/users/{{ $user->id }}">{{ $user->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@stop