<!DOCTYPE html>
<html>
    <head>
        <title>Smart Car Contorl</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <style>
        #div_go {
            position: absolute;
            top: 360px;
            left:140px;
            width:130px;
            height:100px;
        }
        #div_back {
            position:absolute;
            top:580px;
            left:140px;
            width:130px;
            height:100px;
        }
        #div_left {
            position: absolute;
            top: 470px;
            left:10px;
            width:130px;
            height:100px;
        }
        #div_right {
            position: absolute;
            top: 470px;
            left:270px;
            width:130px;
            height:100px;
        }
        </style>
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
                $('#go').bind('touchstart', function(e) {
                    clearInterval(timer1);
                    timer1 = setInterval(function(){
                        doUpScale('10');
                    }, 300);
                });
                $('#go').bind('touchend', function(e) {
                    clearInterval(timer1);
                    timer1 = setInterval(function(){
                        noDownScale('-5');
                    }, 600);
                });
                $('#back').bind('touchstart', function(e) {
                    clearInterval(timer1);
                    timer1 = setInterval(function(){
                        doDownScale('-10');
                    }, 300);

                });
                $('#back').bind('touchend', function(e) {
                    clearInterval(timer1);
                    timer1 = setInterval(function(){
                        noUpScale('5');
                    }, 600);
                });
            });
        </script>
    </head>
    <body>
        <div data-role="header">
            <h1 style="text-align:center;">Smart Car Control</h1>
        </div>
        <div data-role="content">
            <a href='logout.php'rel='external'>logout</a>
            <div style='display:inline-block; width:100%; height:auto; text-align:center;'>
                <img style='width:100%; height:auto;'src='http://192.168.140.64:8080/stream/video.mjpeg'>
            </div>
            <div id='div_go'>
                <a href='javascript:void(0);' data-role='button' id="go" style="background-color: white"><img src='image/up.jpg'></a>
            </div>
            <div id='div_back'>
                <a href='javascript:void(0);' data-role='button'id="back" style="background-color: white"><img src='image/down.jpg'></a>
            </div>
            <div id='div_left'>
                <a href='javascript:void(0);' data-role='button' id="left" style="background-color: white" onclick='sendLeftMsg()'><img src='image/left.jpg'></a>
            </div>
            <div id='div_right'>
                <a href='javascript:void(0);' data-role='button' id="right" style="background-color: white" onclick='sendRightMsg()'><img src='image/right.jpg'></a>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        var ws = 0
        document.addEventListener("DOMContentLoaded", function(){
            if (ws != 0 && ws.readyState != 1) return;
            if ("WebSocket" in window) {
                ws = new WebSocket("ws://192.168.140.64:9999");
                ws.onopen = function() {
                    console.log("connected");
                };
                ws.onmessage = function(event) {
                    var data = event.data.replace(/</gi, "<");
                    data = data.replace(/>/gi, "> ");
                }
                window.onbeforeunload = function(event) {
                    ws.close();
                };
            }
            else {
                console.log("WebSocket NOT supported by your Browser!");
            }
        });
        var scale = 0;
        function sendMoveMsg(speed){
            ws.send('scale_' + speed);
        }
        function sendLeftMsg(){
            ws.send('left');
        }
        function sendRightMsg(){
            ws.send('right');
        }

        var goBtn = document.querySelector('#go');
        var backBtn = document.querySelector('#back');
        var timer1 = null;

        goBtn.addEventListener('mouseup', function(event) {
            clearInterval(timer1);
            timer1 = setInterval(function(){
                noDownScale('-5');
            }, 600);
        });
        goBtn.addEventListener('mousedown', function(event) {
            clearInterval(timer1);
            timer1 = setInterval(function(){
                doUpScale('10');
            }, 300);
        });

        backBtn.addEventListener('mouseup', function(event) {
            clearInterval(timer1);
            timer1 = setInterval(function(){
                noUpScale('5');
            }, 600);

        });
        backBtn.addEventListener('mousedown', function(event) {
            clearInterval(timer1);
            timer1 = setInterval(function(){
                doDownScale('-10');
            }, 300);
        });
        function doUpScale(action){
            scale = parseInt(scale) + parseInt(action);
            if(scale >= 100){
                scale = 100;
            }
            sendMoveMsg(scale);
        }
        function doDownScale(action){
            scale = parseInt(scale) + parseInt(action);
            if(scale <= -100){
                scale = -100;
            }
            sendMoveMsg(scale);
        }

        function noDownScale(action){
            scale = parseInt(scale) + parseInt(action);
            if(scale <= 0){
                scale = 0;
            }
            sendMoveMsg(scale);
        }
        function noUpScale(action){
            scale = parseInt(scale) + parseInt(action);
            if(scale >= 0){
                scale = 0;
            }
            sendMoveMsg(scale);
        }
    </script>
</html>

