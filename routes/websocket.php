<?php

use Illuminate\Http\Request;
use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/
 $namespace = '\App\Http\Controllers\\';

Websocket::on('connect', function ($websocket, Request $request) {
    // called while socket on connect
//    binding webSocket with connected user
//    Websocket::loginUsing($request->user());
    Websocket::loginUsingId($request->user()->id);
    echo "Welcome you have a new user ".Websocket::getUserId()."\n";
});



Websocket::on('sendMsg',$namespace.'WebSocketController@sendMsg');
Websocket::on('join_room',$namespace.'WebSocketController@join_room');
Websocket::on('leave_room',$namespace.'WebSocketController@leave_room');
Websocket::on('sendToRoom',$namespace.'WebSocketController@sendToRoom');
//Websocket::on('sendMsg',function($websocket,$request){
//
//    Websocket::broadcast()->to($request['to'])->emit('receiveMsg',$request['message']);
//    echo $request['to'];
//    Websocket::toUserId($request['to'])->emit('receiveMsg',$request['message']);
//});

Websocket::on('disconnect', function ($websocket) {
    // called while socket on disconnect
});

Websocket::on('example', function ($websocket, $data) {
    $websocket->emit('message', $data);
});

// Websocket::on('test', 'ExampleController@method');