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

    @if (isset($show_upload_btn) && $show_upload_btn)
        <span id="img-upload-btn" class="img-upload-btn btn btn--primary hidden">
            <i class="fa fa-plus"></i>
            <span>Change course banner image...</span>
            <input id="course-img-upload" type="file" name="files[]" />
        </span>
        <div id="progress" class="progress hidden">
            <p class="progress-text">Uploading...</p>
            <div class="progress-bar__container">
                <div class="progress-bar"></div>
            </div>
        </div>
    @endif
</div>