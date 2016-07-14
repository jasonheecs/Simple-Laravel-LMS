{{-- 
    Since this blade template is shared between the create and update views, we need to check if a Course model has been initialised.
    If yes, pass it in as an argument to the canAny method. If not, pass in the Course class name. 
--}}
<?php
    if (isset($course)) {
        $canAnyModel = $course;
    } else {
        $canAnyModel = App\Course::class;
    }
?>

@canAny(['update', 'store'], $canAnyModel)
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
@endcan