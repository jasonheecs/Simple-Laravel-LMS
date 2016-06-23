@extends('layouts.with-sidebar')

@section('pageName', 'js-user-page')

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

        <input type="hidden" name="user-id" id="user-id" value="{{ $user->id }}">

        <ul class="tabs">
            <li class="tab tab--active">
                <a href="#tab1">Details</a>
            </li>
            <li class="tab">
                <a href="#tab2">Lectures</a>
            </li>
            <li class="tab">
                <a href="#tab3">Studies</a>
            </li>
            <div class="tabs__indicator"></div>
        </ul>

        @if (count($errors) > 0)
            <div class="alert alert--danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="tab1" class="panel">
            <div>
                <div class="user__personal-details panel__divider flex flex--space-between flex--bottom">
                    <h2 class="panel__title panel__title--small margin--bottom-none flex__item--1">Personal Details</h2>
                    <div id="user-actions-grp" class="user__button-grp button-group">
                        <a id="edit-profile-btn" class="btn btn--primary btn--md"><i class="icon icon--edit"></i> Edit</a>
                        <form method="POST" class="form--inline" action="{{ url('/users', $user->id) }}">
                            {{ method_field('DELETE') }}
                            <button id="delete-profile-btn" class="btn btn--outline btn--md" type="submit"><i class="icon icon--delete"></i> Delete</button>
                            {!! csrf_field() !!}
                        </form>
                    </div>
                    <div id="content-actions-grp" class="user__button-grp button-group hidden">
                        <a id="save-changes-btn" class="btn btn--primary btn--md"><i class="icon icon--save"></i> Save</a>
                        <a id="cancel-changes-btn" class="btn btn--outline btn--md"><i class="icon icon--cancel"></i> Cancel</a>
                    </div>
                </div>
                <div>
                    <h4 class="user__label">Name</h4>
                    <div id="name-editor" class="user__input">{{ $user->name }}</div>
                    <h4 class="user__label">Email</h4>
                    <div id="email-editor" class="user__input">{{ $user->email }}</div>
                    <h4 class="user__label">Company</h4>
                    <div id="company-editor" class="user__input">{{ $user->company }}</div>
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
                                @if ($user->is('superadmin'))
                                    <input id="super-admin-checkbox" name="isSuperAdmin" type="checkbox" class="toggle--switch toggle-switch--round" checked="true" />
                                @else
                                    <input id="super-admin-checkbox" name="isSuperAdmin" type="checkbox" class="toggle--switch toggle-switch--round" />
                                @endif
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
        </div><!-- end #tab1 -->

        <div id="tab2" class="panel hidden">
            <h2 class="panel__title panel__title--small panel__divider">Is Lecturer In:</h2>
            <div class="grid grid--width-1-1 grid--width-medium-1-2 grid--width-large-1-3 grid--small grid--match">
                @foreach ($user->getAllInstructors() as $instructor)
                    @foreach ($instructor->courses as $course)
                        <div>
                            @include('shared.card-grid-item', [
                                'additional_classes' => 'card--secondary'
                            ])
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div><!-- end #tab2 -->

        <div id="tab3" class="panel hidden">
            <h2 class="panel__title panel__title--small panel__divider">Is Student In:</h2>
            <div class="grid grid--width-1-3 grid--small">
                @foreach ($user->getAllStudents() as $student)
                    @foreach ($student->courses as $course)
                        <div>
                            @include('shared.card-grid-item', [
                                'additional_classes' => 'card--secondary'
                            ])
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div><!-- end #tab3 -->
        
    </div>
</div>
@stop