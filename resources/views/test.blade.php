<script src="{{ asset('public/libs/socket.io/socket.io.js') }}"></script>
<script>
    var socket = io('@env('APP_DOMAIN'):@env('SOCKETIO_PORT')@env('APP_NAMESPACE')');

    socket.on('notification', function(message) {
        console.log(message);
    });
</script>
