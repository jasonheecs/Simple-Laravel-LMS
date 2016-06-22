<div class="topbar__wrapper flex flex--middle flex--space-between">
    <div class="topbar">
        <div class="topbar__content flex flex--middle flex--wrap">
            <div class="text--center">
                <a href="{{ url('/home') }}">
                    <img class="topbar__logo" src="{{ asset('img/efusion-logo.png') }}" alt="Efusion Technology" />
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
                    @if (Auth::user()->canManageUsers())
                        <li>
                            <a class="topbar-nav__link" href="{{ url('/users') }}">
                                @include('svg.users')Users
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>

    @if (isset(Auth::user()->avatar))
    <div class="topbar__avatar" style="background-image:url({{ Auth::user()->avatar }})">
    </div>
    @endif

</div>