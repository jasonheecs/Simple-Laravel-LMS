@extends('layouts.with-sidebar')

@section('content')
    <div class="panel panel--default">
        <h1 class="panel__heading">All Users</h1>
        <div class="panel__content">
            <table>
                <tr>
                    <th>Name</th>
                    @foreach ($roles as $role)
                        <th>Is {{ ucwords($role->name) }}</th>
                    @endforeach
                    <th>Email</th>
                </tr>
                @foreach ($users as $user)
                    <tr>
                        <td><a href="/users/{{ $user->id }}">{{ $user->name }}</a></td>
                        @foreach ($roles as $role)
                            <td>
                                @if ($user->is($role->name))
                                    <input type="checkbox" name="{{ $user->id }}_{{ $role->id }}" checked="true" />
                                @else
                                    <input type="checkbox" name="{{ $user->id }}_{{ $role->id }}" />
                                @endif
                            </td>
                        @endforeach
                        <td><a href="mailto:{{ $user->email }}" class="link--muted">{{ $user->email }}</a></td>
                    </tr>
                @endforeach
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