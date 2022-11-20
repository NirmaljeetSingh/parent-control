<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- <script src="https://cdn.socket.io/4.4.1/socket.io.js" ></script> -->
    <!-- <script src="https://cdn.socket.io/4.4.0/socket.io.min.js" integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous"></script> -->
    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js" integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous"></script>


    
    <script>
        const socket = io('http://localhost:8099',{
  withCredentials: true,
  extraHeaders: {
    "my-custom-header": "abcd"
  }
});
        socket.on("connect", () => {
            console.log(socket.id); // ojIckSD2jqNzOqIrAGzL
        });
    </script>
    <!-- <div class=”container”>
    <h1>Team A Score</h1>
    <div id=”team1_score”></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"> </script>
    <script>
    var sock = io("{{ env('PUBLISHER_URL') }}:{{ env('BROADCAST_PORT') }}");
    sock.on('action-channel-one:App\\Events\\ActionEvent', function (data){
        //data.actionId and data.actionData hold the data that was broadcast
        //process the data, add needed functionality here
        var action = data.actionId;
        var actionData = data.actionData;
    if(action == "score_update" && actionData.team1_score) {
            $("#team1_score").html(actionData.team1_score);
        }
    });
    </script> -->
    <!-- <script type="module">
        import { io } from "https://cdn.socket.io/4.4.1/socket.io.esm.min.js";

        const socket = io();
    </script> -->
</body>
</html>