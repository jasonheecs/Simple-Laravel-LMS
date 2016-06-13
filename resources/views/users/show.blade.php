@extends('layouts.with-sidebar')

@section('pageName', 'js-user-page')

@section('hero')
    @include('shared.hero', [
        'hero_title' => $user->name
    ])
@stop

@section('content')
<div class="container container--single-col">
    <div id="user-panel" class="panel panel--default panel--first">

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

        <div id="tab1" class="panel__content margin--top">
            <div class="panel panel--pad-lg">
                <div class="panel__heading flex flex--space-between flex--flex-end">
                    <h2 class="panel__heading--small margin--bottom-none flex__item--1">Personal Details</h2>
                    <div id="user-actions-grp" class="button-group">
                        <a id="edit-profile-btn" class="btn btn--primary btn--md"><i class="icon icon--edit"></i> Edit</a>
                        <a id="delete-profile-btn" class="btn btn--outline btn--md"><i class="icon icon--delete"></i> Delete</a>
                    </div>
                    <div id="content-actions-grp" class="button-group hidden">
                        <a id="save-changes-btn" class="btn btn--primary btn--md"><i class="icon icon--save"></i> Save</a>
                        <a id="cancel-changes-btn" class="btn btn--outline btn--md"><i class="icon icon--cancel"></i> Cancel</a>
                    </div>
                </div>
                <div class="panel__content">
                    <h4 class="user__label">Name</h4>
                    <div id="name-editor" class="user__input">{{ $user->name }}</div>
                    <h4 class="user__label">Email</h4>
                    <div id="email-editor" class="user__input">{{ $user->email }}</div>
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
        </div><!-- end #tab1 -->

        <div id="tab2" class="panel__content margin--top hidden">
            <div class="items-grid items-grid--3">
                <div class="panel items-grid-panel panel--pad-lg">
                    <h2 class="items-grid__heading">Is Lecturer In:</h2>
                    @foreach ($user->getAllInstructors() as $instructor)
                        @foreach ($instructor->courses as $course)
                            @include('shared.card-grid-item', [
                                'additional_classes' => 'card--secondary'
                            ])
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div><!-- end #tab2 -->

        <div id="tab3" class="panel__content margin--top hidden">
            <div class="items-grid items-grid--3">
                <div class="panel items-grid-panel panel--pad-lg">
                    <h2 class="items-grid__heading">Is Student In:</h2>
                    @foreach ($user->getAllStudents() as $student)
                        @foreach ($student->courses as $course)
                            @include('shared.card-grid-item', [
                                'additional_classes' => 'card--secondary'
                            ])
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div><!-- end #tab3 -->
    </div>
</div>
@stop