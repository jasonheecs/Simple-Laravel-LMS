@extends('layouts.with-sidebar')

@section('pageName', 'js-create-user-page')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@stop

@section('hero')
    @include('shared.hero', [
        'sub_template' => 'users.hero-sub'
    ])
@stop

@section('content')
<div class="container container--single-col">
    <div id="user-panel" class="panel panel--default panel--first">

        <h1>Create New User</h1>

        <div id="tab1" class="panel__content margin--top">
            <div class="panel panel--pad-lg">
                <div class="panel__heading flex flex--space-between flex--flex-end">
                    <h2 class="panel__heading--small margin--bottom-none flex__item--1">Personal Details</h2>
                    <div id="content-actions-grp" class="button-group">
                        <a id="save-changes-btn" class="btn btn--primary btn--md"><i class="icon icon--save"></i> Save</a>
                        <a id="cancel-changes-btn" class="btn btn--outline btn--md"><i class="icon icon--cancel"></i> Cancel</a>
                    </div>
                </div>
                <div class="panel__content">
                    <h4 class="user__label">Name</h4>
                    <div id="name-editor" class="user__input"></div>
                    <h4 class="user__label">Email</h4>
                    <div id="email-editor" class="user__input"></div>
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
                                <input id="super-admin-checkbox" name="isSuperAdmin" type="checkbox" class="toggle--switch toggle-switch--round" />
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
                            <input id="admin-checkbox" name="isAdmin" type="checkbox" class="toggle--switch toggle-switch--round" />
                            <label for="admin-checkbox"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end #tab1 -->
    </div>
</div>
@stop