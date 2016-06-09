@extends('layouts.with-sidebar')

@section('hero')
    @include('shared.hero', [
                            'hero_image' => 'img/bg/users.jpg',
                            'hero_title' => 'All Users',
                            'hero_subtitle' => 'If you are not an admin, turn back now.'
                            ])
@stop

@section('content')
    <div id="users-panel" class="panel panel--default panel--first">
        <div class="users__header">@include('svg.user'){{ count($users) }} Users</div>
        <div class="panel__content">
            <table class="table table--bordered table--hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="checkall" /></th>
                        <th>&nbsp;</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>
                            @if ($user->is('admin'))
                                @include('svg.hammer')
                            @elseif ($user->is('superadmin'))
                                @include('svg.star')
                            @endif
                        </td>
                        <td><a href="/users/{{ $user->id }}">{{ $user->name }}</a></td>
                        <td>Efusion Technology</td>
                        <td><a href="mailto:{{ $user->email }}" class="link--muted">{{ $user->email }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @if (Auth::user()->canManageUsers())
                <ul id="user-admin-actions" class="list list--inline button-group button-group--right margin--top admin-actions-group">
                    <li>
                        <form method="GET" action="{{ url('/users/create') }}">
                            <button id="create-user-btn" class="btn btn--primary" type="submit">Create User</button>
                        </form>
                    </li>
                </ul>
            @endif
        </div>
    </div>
@stop