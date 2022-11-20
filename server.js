'use strict';
var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
require('dotenv').config();

var redisPort = process.env.REDIS_PORT;
var redisHost = process.env.REDIS_HOST;
var ioRedis = require('ioredis');
var redis = new ioRedis(redisPort, redisHost);
redis.subscribe('action-channel-one');
redis.on('message', function (channel, message) {
  message  = JSON.parse(message);
  io.emit(channel + ':' + message.event, message.data);
});

redis.on("connect", (socket) => {
    console.log('connected',socket); // false
});
redis.on("subscribe", () => {
    console.log('subscribe'); // false
});

var broadcastPort = process.env.BROADCAST_PORT;
var APP_URL = process.env.APP_URL;
server.listen(broadcastPort,{
    cors: {
      origin: "*",
      methods: ["GET", "POST"],
      allowedHeaders: ["my-custom-header",'Access-Control-Allow-Origin'],
      credentials: true
    }}, function () {
  console.log('Socket server is running.',broadcastPort);
});