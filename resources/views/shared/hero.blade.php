@if (isset($hero_image))
<div class="hero container container--centered container--full-width" style="background-image: url('{{ asset($hero_image) }}')">
@else
<div class="hero container container--centered container--full-width">
@endif
    @if (isset($hero_title) || isset($hero_subtitle))
    <div class="hero__heading">
        @if (isset($hero_title))
        <h1 class="hero__title">{{ $hero_title }}</h1>
        @endif
        @if (isset($hero_subtitle))
        <p class="hero__subtitle">{{ $hero_subtitle }}</p>
        @endif
    </div>
    @endif

    @if(isset($sub_template))
        @include($sub_template)
    @endif
</div>