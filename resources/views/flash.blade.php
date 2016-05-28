@if (session()->has('flash_message'))
    <div class="alert alert--{{ strtolower(session('flash_message_level')) }}">
        {{ session('flash_message') }}
    </div>
@endif