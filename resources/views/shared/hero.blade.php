@if (isset($hero_image))
    <div class="hero container container--centered container--full-width" style="background-image: url('{{ asset($hero_image) }}')">
@else
    <div class="hero container container--centered container--full-width">
@endif
    <div class="hero--heading">
        <h1 class="hero--title">All Courses</h1>
        <p class="hero--subtitle">A directory of useful courses</p>
    </div>
</div>