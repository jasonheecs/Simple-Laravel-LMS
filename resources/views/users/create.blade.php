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
<div class="container">
    <div id="user-panel" class="panel panel--default">

        <h1>Create New User</h1>

        @if (count($errors) > 0)
            <div class="alert alert--danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="create-user" class="panel">
            <form action="{{ url('/users') }}" method="POST">
                <input type="hidden" id="user-avatar" name="avatar" value="">

                <div>
                    <div class="panel__divider flex flex--space-between flex--bottom flex--wrap">
                        <h2 class="panel__title panel__title--small margin--bottom-none flex__item--1">Personal Details</h2>
                        <div id="content-actions-grp" class="button-group">
                            <button id="save-changes-btn" class="btn btn--primary btn--md" type="submit" name="save" value="save"><i class="icon icon--save"></i> Save</button>
                            <button id="cancel-changes-btn" class="btn btn--outline btn--md" type="submit" name="cancel" value="cancel" formnovalidate><i class="icon icon--cancel"></i> Cancel</button>
                        </div>
                    </div>
                    <div>
                        <div class="form-group form-group--lg">
                            <label class="user__label" for="user-name">Name</label>
                            <input type="text" name="name" id="user-name" class="form-control form-control--secondary" placeholder="Enter User's name" autofocus required />
                        </div>
                        <div class="form-group form-group--lg">
                            <label class="user__label" for="user-email">Email</label>
                            <input type="email" name="email" id="user-email" class="form-control form-control--secondary" placeholder="Enter User's email" required />
                        </div>
                        <div class="form-group form-group--lg">
                            <label class="user__label" for="user-company">Company</label>
                            <input type="text" name="company" id="user-company" class="form-control form-control--secondary" placeholder="Enter User's company" />
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="panel__title panel__title--small panel__divider">Permissions</h2>
                    <div class="margin--top">
                        <div class="flex">
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
                        <div class="flex">
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

                {!! csrf_field() !!}
            </form>
        </div><!-- end #create-user -->
    </div>
</div>
@stop