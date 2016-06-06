@if (isset($hero_image))
    <div class="hero container container--centered container--full-width" style="background-image: url('{{ asset($hero_image) }}')">
@else
    <div class="hero container container--centered container--full-width">
@endif
    <div class="hero--heading">
        @if (isset($hero_title))
        <h1 class="hero--title">{{ $hero_title }}</h1>
        @endif
        @if (isset($hero_subtitle))
        <p class="hero--subtitle">{{ $hero_subtitle }}</p>
        @endif
    </div>
</div>