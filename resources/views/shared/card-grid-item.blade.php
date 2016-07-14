@if (isset($additional_classes))
    <div class="card {{ $additional_classes }}">
@else
    <div class="card">
@endif
    @can('show', $course)
    <a href="{{ url('/courses', $course->id) }}">
    @else
    <a href="javascript:void(0)" onclick="window.notify('You do not have permission to access this page', 'danger');">
    @endcan
        @if (isset($course->image))
            <figure class="card-figure">
                <img src={{ $course->image }} />
                <!-- <img src="http://placehold.it/400x175" /> -->
            </figure>
        @endif
        <h3 class="card-title">{{ $course->title }}</h3>
    </a>
</div>