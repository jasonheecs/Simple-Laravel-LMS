@if (isset($additional_classes))
    <div class="card {{ $additional_classes }}">
@else
    <div class="card">
@endif
    <a href="{{ url('/courses', $course->id) }}">
        @if (isset($course->image))
            <figure class="card-figure">
                <img src={{ $course->image }} />
                <!-- <img src="http://placehold.it/400x175" /> -->
            </figure>
        @endif
        <h3 class="card-title">{{ $course->title }}</h3>
    </a>
</div>