<div class="topbar__wrapper flex flex--middle flex--space-between">
    <div class="topbar">
        <div class="topbar__content flex flex--middle">
            <div class="topbar__logo-wrapper">
                <a href="{{ url('/home') }}" class="topbar__logo-link">
                    {{-- <img class="topbar__logo" src="{{ asset('img/logo.png') }}" /> --}}
                    Demo LMS
                </a>
                <h3 class="topbar__subtitle">Learning Management System</h3>
            </div>
            <nav class="topbar-nav">
                <ul class="topbar-nav__list list">
                    <li>
                        <a class="topbar-nav__link" href="{{ url('/courses') }}">
                            @include('svg.courses')Courses
                        </a>
                    </li>
                    <li>
                        <a class="topbar-nav__link" href="#">
                            @include('svg.settings')Settings
                        </a>
                    </li>
                    @can('index', Auth::user())
                        <li>
                            <a class="topbar-nav__link" href="{{ url('/users') }}">
                                @include('svg.users')Users
                            </a>
                        </li>
                    @endcan
                </ul>
            </nav>
        </div>
    </div>

    @if (isset(Auth::user()->avatar))
    <div class="topbar__avatar" style="background-image:url({{ Auth::user()->avatar }})">
    </div>
    @endif

</div>