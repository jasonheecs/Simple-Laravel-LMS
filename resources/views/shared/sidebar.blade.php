<section class="sidebar">
    <div class="avatar">
        <figure class="avatar-figure">
        
            @if (isset(Auth::user()->avatar))
            <img src="{{ Auth::user()->avatar }}" class="avatar__img" width="150" height="150" />
            @else
            <img src="//api.adorable.io/avatars/150/{{ Auth::user()->name }}" class="avatar__img" width="150" height="150" />
            @endif
            
            <figcaption class="avatar__name">{{ Auth::user()->name }}</figcaption>
        </figure>
        <span class="avatar__status">Enrolled in 1 course(s)</span>
    </div>
    <nav class="sidebar-nav">
        <ul class="sidebar-nav__links">
            <li>
                <a class="sidebar-nav__link" href="{{ url('/') }}">
                    @include('svg.home')Home
                </a>
            </li>
            <li>
                <a class="sidebar-nav__link" href="{{ url('/courses') }}">
                    @include('svg.courses')Courses
                </a>
            </li>
            <li>
                <a class="sidebar-nav__link" href="#">
                    @include('svg.settings')Settings
                </a>
            </li>
            @can('index', Auth::user())
                <li>
                    <a class="sidebar-nav__link" href="{{ url('/users') }}">
                        @include('svg.users')Users
                    </a>
                </li>
            @endcan
        </ul>
    </nav>
</section>