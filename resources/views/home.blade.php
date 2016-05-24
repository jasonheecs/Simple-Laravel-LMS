@extends('layouts.with-sidebar')

@section('content')
<div class="dashboard">
    <div class="panel panel-default">
        <div class="panel-heading">Dashboard</div>

        <div class="panel-body">
            You are logged in!
        </div>
        {{ Auth::user()->is('admin') }}
    </div>
</div>
@endsection
