@if (session()->has('flash_message'))
    <div class="alert alert--{{ ucwords(session('flash_message_level')) }}">
        {{ session('flash_message') }}
    </div>
@endif