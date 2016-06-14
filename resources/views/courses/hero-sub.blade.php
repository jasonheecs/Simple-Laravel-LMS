@if (Auth::user()->canEdit($course))
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