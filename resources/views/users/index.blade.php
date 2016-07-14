@extends('layouts.with-sidebar')

@section('pageName', 'js-users-page')

@section('hero')
    @include('shared.hero', [
        'hero_image' => 'img/bg/users.jpg',
        'hero_title' => 'All Users',
        'hero_subtitle' => 'If you are not an admin, turn back now.'
    ])
@stop

@section('content')
<div class="container">
    <div id="users-panel" class="panel panel--default">
        <div class="users__header flex flex--space-between flex--middle">
            <div>@include('svg.user'){{ count($users) }} Users</div>
            @can('store', Auth::user())
                <ul id="user-admin-actions" class="list list--inline button-group button-group--right">
                    <li>
                        <form method="GET" action="{{ url('/users/create') }}">
                            <button id="create-user-btn" class="btn btn--primary" type="submit"><i class="icon icon--create-user"></i> Create User</button>
                        </form>
                    </li>
                </ul>
            @endcan
        </div>

        <div class="padding--bottom-lg">
            <table id="users-list" class="table table--bordered table--hover table--responsive">
                <thead>
                    <tr>
                        <th class="text--center"><input type="checkbox" name="checkall" /></th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="users__checkbox"><input type="checkbox" /></td>
                        <td class="users__status-icon">
                            @if ($user->is('superadmin'))
                                @include('svg.star')
                            @elseif ($user->is('admin'))
                                @include('svg.hammer')
                            @endif
                        </td>
                        <td class="users__avatar-container">
                            @if ($user->avatar)
                            <a href="/users/{{ $user->id }}">
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="users__avatar" width="28" height="28">
                            </a>
                            @endif
                        </td>
                        <td data-th="Name"><a href="/users/{{ $user->id }}">{{ $user->name }}</a></td>
                        <td data-th="Company">{{ $user->company }}</td>
                        <td data-th="Email"><a href="mailto:{{ $user->email }}" class="link--muted">{{ $user->email }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop