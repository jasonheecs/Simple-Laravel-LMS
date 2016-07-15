@if (session()->has('flash_message'))
    <script type="text/javascript">
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/flash?cache='+ (Math.random()*1000000));
        xhr.addEventListener('load', function(evt) {
            if (xhr.readyState === 4 && (xhr.status >= 200 && xhr.status < 300)) {
                if (xhr.responseText) {
                    response = JSON.parse(xhr.responseText);
                }
                if (response.message && response.message.length){
                    response = JSON.parse(xhr.responseText);
                    window.notify(response.message, response.message_level);
                }
            }
        });

        xhr.send();
    </script>
@endif