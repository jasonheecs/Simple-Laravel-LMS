@extends('layouts.with-sidebar')
@section('hero')
    @include('shared.hero', [
                            'hero_title' => $user->name
                            ])
@stop

@section('content')
    <div class="container container--single-col">
        <div class="panel panel--default panel--first">
            <ul class="tabs">
                <li class="tab">
                    <a href="#">Details</a>
                </li>
                <li class="tab">
                    <a href="#">Lectures</a>
                </li>
                <li class="tab">
                    <a href="#">Studies</a>
                </li>
            </ul>

            <div class="panel__content margin--top">
                <div class="panel panel--pad-lg">
                    <h2 class="panel__heading panel__heading--small">Personal Details</h2>
                    <div class="panel__content">
                        <h4 class="user__label">Name</h4>
                        <div class="user__input">{{ $user->name }}</div>
                        <h4 class="user__label">Email</h4>
                        <div class="user__input">{{ $user->email }}</div>
                    </div>
                </div>
                
                <div class="panel panel--pad-lg">
                    <h2 class="panel__heading panel__heading--small">Permissions</h2>
                    <div class="panel__content">
                        <div class="container container--full-width">
                            <div class="flex__item--5">
                                Is this user a Super Administrator?
                                <small>
                                    Super Administrators can:
                                    <ul class="list list--plain list--small">
                                        <li>Create/edit/delete users</li>
                                        <li>Create/edit/delete courses</li>
                                        <li>Create/edit/delete lessons</li>
                                    </ul>
                                </small>
                            </div>
                            <div class="flex__item--1">
                                <div class="toggle">
                                    @if ($user->is('superadmin'))
                                        <input id="super-admin-checkbox" name="isSuperAdmin" type="checkbox" class="toggle--switch toggle-switch--round" checked="true" />
                                    @else
                                        <input id="super-admin-checkbox" name="isSuperAdmin" type="checkbox" class="toggle--switch toggle-switch--round" />
                                    @endif
                                    <label for="super-admin-checkbox"></label>
                                </div>
                            </div>
                        </div>
                        <div class="container container--full-width">
                            <div class="flex__item--5">
                                Is this user an Administrator?
                                <small>
                                    Administrators can:
                                    <ul class="list list--plain list--small">
                                        <li>Create users</li>
                                        <li>Create/edit courses</li>
                                        <li>Create/edit/delete lessons</li>
                                    </ul>
                                </small>
                            </div>
                            <div class="flex__item--1">
                                @if ($user->is('admin'))
                                    <input id="admin-checkbox" name="isAdmin" type="checkbox" class="toggle--switch toggle-switch--round" checked="true" />
                                @else
                                    <input id="admin-checkbox" name="isAdmin" type="checkbox" class="toggle--switch toggle-switch--round" />
                                @endif
                                <label for="admin-checkbox"></label>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
        </div>
    </div>
@stop