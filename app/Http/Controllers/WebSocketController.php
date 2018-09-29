<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\User;
use Illuminate\Http\Request;
use SwooleTW\Http\Websocket\Facades\Websocket;
use SwooleTW\Http\Websocket\Facades\Room;


class WebSocketController extends Controller
{
    protected static function json($array)
    {
        return addslashes(json_encode($array));
    }

    public static function room_clients($type,$data){
        $type=='join'?Rooms::create($data):Rooms::where(['name'=>$data['name'],'user_id'=>$data['user_id']])->delete();
    }

    function sendMsg($websocket,$request)
    {
        Websocket::broadcast()->to($request['to'])->emit('receiveMsg',$request['message']);
        $json=addslashes(json_encode(['user1'=>'php','user2'=>'swoole']));
        Websocket::broadcast()->to($request['to'])->emit('sendJson',$json);
    }

    function join_room($websocket,$request)
    {
//        $myRooms=Room::getRooms(Websocket::getUserId());

        $UID=Websocket::getUserId();

        Websocket::join($request['room']);
        Room::add($UID, $request['room']);
        self::room_clients('join',['name'=>$request['room'],'user_id'=>$UID]);
        $usersList=Rooms::where('name',$request['room'])->with('user_id')->get();
        $data=self::json(['user'=>User::find($UID),'usersList'=>$usersList]);
//        Websocket::to($request['room'])->emit('joind',$data);
        Websocket::broadcast()->to($request['room'])->emit('joind',$data);
//        var_dump(Room::getRooms($UID));
    }
   function leave_room($websocket,$request)
    {
        $UID=Websocket::getUserId();
        Room::delete($UID, $request['room']);
        self::room_clients('leave',['name'=>$request['room'],'user_id'=>$UID]);
        $data=self::json(['user'=>User::find($UID)]);
//        Websocket::to($request['room'])->emit('leaved',$data);
        Websocket::broadcast()->to($request['room'])->emit('leaved',$data);

    }

    function sendToRoom($websocket,$request)
    {
        $UID=Websocket::getUserId();
        $data=self::json(['user'=>User::find($UID),'message'=>$request['message']]);
        Websocket::to($request['room'])->emit('new_msg',$data);
    }
}
