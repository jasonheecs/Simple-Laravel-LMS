@if (isset($user))
<div class="hero__heading">
    <img id="user-avatar-img" src="{{ $user->avatar }}" />
    <h1 id="hero-user-name" class="hero__title hero__title--small">{{ $user->name }}</h1>
</div>
@else
<div class="hero__heading">
    <img id="user-avatar-img" />
    <h1 id="hero-user-name" class="hero__title hero__title--small"></h1>
</div>
@endif

<span id="img-upload-btn" class="img-upload-btn btn btn--primary hidden">
    <i class="icon icon--create-avatar"></i>
    <span>Change User Avatar...</span>
    <input id="user-img-upload" type="file" name="files[]" />
</span>
<div id="progress" class="progress hidden">
    <p class="progress-text">Uploading...</p>
    <div class="progress-bar__container">
        <div class="progress-bar"></div>
    </div>
</div>