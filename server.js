var
    app            = require('express')(),
    http           = require('http').Server(app),
    io             = require('socket.io')(http),
    Redis          = require('ioredis'),
    redis          = new Redis(),
    redisClient    = require("redis").createClient(),
    PHPUnserialize = require('php-unserialize'),
    crypto         = require('crypto'),
    cookie         = require('cookie');

require('dotenv').config();

var port = process.env.SOCKETIO_PORT;
    namespace = process.env.APP_NAMESPACE;

var userIdKey = 'login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d';

var users = 0,
    user = [];

//---------------------------------------------------------------------------------------------------------------------

io = io.of(namespace);

redis.psubscribe('*', function(err, count) { });

redis.on('pmessage', function(subscribed, channel, message) {

    console.log('Message recieved on "' + channel + '": ' + message);
    console.log('--------------------')

    message = JSON.parse(message);

    io.to(message.event).emit(channel, message.data);

});

io.on('connection', function(socket) {

    //-----------------------------------------------------------------------------------------------------------------

    var laravel_session = cookie.parse(socket.handshake.headers.cookie).laravel_session;

    var laravel_cookie = JSON.parse(new Buffer(laravel_session, 'base64'));

    var iv     = new Buffer(laravel_cookie.iv, 'base64');
    var value  = new Buffer(laravel_cookie.value, 'base64');
    var key    = process.env.APP_KEY;

    var decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);

    decipher.setAutoPadding(false)

    var dec = Buffer.concat([decipher.update(value), decipher.final()]);

    var session_id = PHPUnserialize.unserialize(dec);

    redisClient.get('laravel:' + session_id, function( err, result ) {

        var data = PHPUnserialize.unserialize(PHPUnserialize.unserialize(result));

        if (userIdKey in data && 'currentHub' in data) { // Authenticated

            userId = data[userIdKey];
            hubId = data['currentHub'];

            socket.userId = userId;
            socket.hubId = hubId;

            socket.join(userId + '.' + hubId);

            console.log('User #' + userId + ' connected in hub #' + hubId);

        } else { // NonAuthenicated

            console.log('Guest connected and kicked out');

            socket.disconnect();
        }
    });

    //-----------------------------------------------------------------------------------------------------------------

    socket.on('disconnect', function () {

    });

});

http.listen(port, function(){

    console.log('Listening namespace ' + namespace + ' on Port ' + port);

});
