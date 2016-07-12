@if (session()->has('flash_message'))
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            window.notify('{{ session('flash_message') }}', '{{ strtolower(session('flash_message_level')) }}');
        });
    </script>
@endif