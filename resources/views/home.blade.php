@extends('layouts.app')

@section('content')
    <script type="text/javascript">

        socket.on('receiveMsg',function(data){
            document.getElementById('messageContent').innerHTML="<h2>"+data+"</h2>";
        });

        socket.on('sendJson',function(data){
            var obj=JSON.parse(data);
            console.log(obj.user2);
        });
        function sendMsg(){
            var message=document.getElementById('message').value;
            var to=document.getElementById('to').value;
            socket.emit('sendMsg',{
                message:message,
                to:to
            });
        }
    </script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <input type="text" name="to" id="to">
                    <textarea name="message" id="message"></textarea>
                    <button id="send" onclick="sendMsg();">Send Message</button>
                    <div id="messageContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
