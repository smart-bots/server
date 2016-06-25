var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('test-channel', function(err, count) {
});

redis.on('message', function(channel, message) {
    console.log('Message Recieved: ' + message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

// io.on('connection', function(socket) {
//     //
// });

// redis.psubscribe('*', function(err, count) {
//     //
// });

// redis.on('pmessage', function(subscribed, channel, message) {
//     console.log('Message Recieved: ' + message);
//     message = JSON.parse(message);
//     io.emit(channel + ':' + message.event, message.data);
// });

http.listen(3189, function(){
    console.log('Listening on Port 3189');
});
