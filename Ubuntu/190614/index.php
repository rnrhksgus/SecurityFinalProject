<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width; initial-scale=1.0" />
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                <?php
                $con = mysqli_connect('localhost', 'user', '123456', 'user_db');
                $php_self = $_SERVER['PHP_SELF'];
                $remote_addr = $_SERVER['REMOTE_ADDR'];
                exec("arp -H ether -n -a ".$remote_addr."",$values);
                $parts = explode(' ',$values[0]);
                $mac_addr = $parts[3];
                $remote_port = $_SERVER['REMOTE_PORT'];
                $script_name = $_SERVER['SCRIPT_NAME'];
                $request_uri = $_SERVER['REQUEST_URI'];
                $php_self = stripslashes($php_self);
                $remote_addr = stripslashes($remote_addr);
                $mac_addr = stripslashes($mac_addr);
                $remote_port = stripslashes($remote_port);
                $script_name = stripslashes($script_name);
                $request_uri = stripslashes($request_uri);
                $php_self = mysqli_real_escape_string($con, $php_self);
                $remote_addr = mysqli_real_escape_string($con, $remote_addr);
                $mac_addr = mysqli_real_escape_string($con, $mac_addr);
                $remote_port = mysqli_real_escape_string($con, $remote_port);
                $script_name = mysqli_real_escape_string($con, $script_name);
                $request_uri = mysqli_real_escape_string($con, $request_uri);
                $sql = "insert into server values ('$php_self', '$remote_addr', '$mac_addr', '$remote_port', '$script_name','$request_uri', now())";
                $result = mysqli_query($con, $sql);
                mysqli_free_result($result);
                mysqli_close($con);
                ?>
            });
        </script>
    </head>
</html>
<?php
    session_start();
    if(!isset($_SESSION['user_id'])) {
        echo "<meta http-equiv='refresh' content='0;url=login.php'>";
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    echo "<p>Hello. $user_name($user_id)<a href='logout.php'rel='external'>logout</a> <a href='admin.php'rel='external'>ADMIN</a></p>";
    echo "<div style='display:inline-block; width:100%; height:auto;'><img src='http://192.168.140.55:8080/stream/video.mjpeg'></div>";
    echo "<div id='chatArea' style='display:inline-block; overflow-y: auto; width:100%; height: 100px;'></div>";
    echo "<button onclick='sendGoMsg()'>go</button>";
    echo "<button onclick='sendBackMsg()'>back</button>";
    echo "<button onclick='sendLeftMsg()'>left</button>";
    echo "<button onclick='sendRightMsg()'>right</button>";
    echo "<button onclick='sendMoveMsg()'>move</button>";
    echo "<button onclick='sendStopMsg()'>stop</button>";
    echo "<input id='count' type='number' value='0' />";
    echo "<button id='plus' onmousedown='sendScaleMsg()'>plus</button>";
    echo "<button id='minus' onmousedown='sendScaleMsg()'>minus</button>";
?>
<script type="text/javascript">
    var ws = 0
    document.addEventListener("DOMContentLoaded", function(){
        if (ws != 0 && ws.readyState != 1) return;
        if ("WebSocket" in window) {
            // alert("WebSocket is supported by your Browser!");
            ws = new WebSocket("ws://192.168.140.55:9999");
            ws.onopen = function() {
                console.log("connected");
            };
            ws.onmessage = function(event) {
                var data = event.data.replace(/</gi, "<");
                data = data.replace(/>/gi, "> ");
                $("#chatArea").append(data + "<br />");
                $("#chatArea").scrollTop($("#chatArea")[0].scrollHeight);
            }
            window.onbeforeunload = function(event) {
                ws.close();
            };
        }
        else {
            console.log("WebSocket NOT supported by your Browser!");
        }
    });
    function sendMsg() {
        ws.send(document.getElementById("msg").value);
        document.getElementById("msg").value = '';
    }
    function sendGoMsg(){
        ws.send('go');
    }
    function sendBackMsg(){
        ws.send('back');
    }
    function sendLeftMsg(){
        ws.send('left');
    }
    function sendRightMsg(){
        ws.send('right');
    }
    function sendStopMsg(){
        ws.send('stop');
    }
    function sendMoveMsg(){
        ws.send('move');
    }
    function sendScaleMsg(){
        ws.send(count.value);
    }

    var plusEle = document.querySelector('#plus');
    var minusEle = document.querySelector('#minus');
    var isPressed = false;

    var playAlert = null;

    plusEle.addEventListener('mouseup', function(event) {
        isPressed = false;
        playAlert = setInterval(function() {
            count.value = count.value - 5;
            if (count.value < 0)
                count.value = 0;
	    else
        	sendScaleMsg();
        }, 1000);
    });

    plusEle.addEventListener('mousedown', function(event) {
        clearInterval(playAlert);
        isPressed = true;
        doInterval('10');
    });

    minusEle.addEventListener('mouseup', function(event) {
        isPressed = false;
    });

    minusEle.addEventListener('mousedown', function(event) {
        isPressed = true;
        doInterval('-10');
    });
    function doInterval(action) {
        if (isPressed) {
            var countEle = document.querySelector('#count');
            count.value = parseInt(count.value) + parseInt(action);

            setTimeout(function() {
                doInterval(action);
                if(count.value>100){
                    count.value = 100
                }
                if(count.value<0){
                    count.value = 0
                }
                sendScaleMsg();
            }, 200);
        }
    };
</script>
