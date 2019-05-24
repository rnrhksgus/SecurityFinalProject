<!DOCTYPE html>
<meta charset="utf-8" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript">
    var ws = 0
    document.addEventListener("DOMContentLoaded", function(){
        if (ws != 0 && ws.readyState != 1) return;
        if ("WebSocket" in window) {
            // alert("WebSocket is supported by your Browser!");
            ws = new WebSocket("ws://192.168.40.20:8765");
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
</script>
<?php
    session_start();
    if(!isset($_SESSION['user_id'])) {
        echo "<meta http-equiv='refresh' content='0;url=login.php'>";
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    echo "<p>안녕하세요. $user_name($user_id)님</p>";
    echo "<div style='border:1px solid black; display:inline-block; width:300px; height:300px'><img src='192.168.140.69:8081'></div>";
    echo "<p><a href='logout.php'rel='external'>로그아웃</a></p>";
    echo "<button onclick='sendGoMsg()'>go</button>";
    echo "<button onclick='sendBackMsg()'>back</button>";
    echo "<button onclick='sendLeftMsg()'>left</button>";
    echo "<button onclick='sendRightMsg()'>right</button>";
?>


