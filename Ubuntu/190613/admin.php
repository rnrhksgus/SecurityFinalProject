<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Smart Car Admin</title>
        <style>
            body {
                margin: 0 auto;
                padding: 0;
                width: 100%;
                background:#f2f4f7;
                overflow-y: scroll;
            }

            div#top {
                margin: 0 auto;
                text-align: center;
                border: 1px solid #d1d8e4;
                width: 1080px;
                height: 150px;
                display: grid;
                line-height: 150px;
                grid-template-columns: 220px 1fr;
                background: white;
            }

            div#top_icon {
                margin: auto 10px;
                width: 200px;
                height: 100px;
                font-size: 32px;
                line-height: 100px;
                text-align: center;
                display: inline-block;
                /* border: 1px solid #d1d8e4; */
            }

            h1#top_h1 {
                display: inline-block;
                text-align: center;
                line-height: 100px;
                border: 1px solid #d1d8e4;
            }

            div#last_login {
                margin: 0 auto;
                margin-top: 10px;
                width: 1080px;
                height: 50px;
                color:white;
                line-height: 50px;
                text-align: center;
                background: navy;
            }

            div#session {
                margin: 0 auto;
                margin-top: 10px;
                width: 1080px;
                height: 400px;
                display: grid;
                grid-template-columns: 363px 363px 1fr;
            }

            div#session_left {
                margin: 0;
                padding: 0;
                width: 351px;
                background: white;
                border: 1px solid #d1d8e4;
                display: block;
                grid-template-rows: 400px;
                overflow-y:scroll;
            }

            div#session_middle {
                margin:0;
                padding: 0;
                width: 351px;
                background: white;
                border: 1px solid #d1d8e4;
            }

            div#session_right {
                padding: 0;
                background: white;
                border: 1px solid #d1d8e4;
            }

            div#board {
                margin: 0 auto;
                margin-top: 10px;
                width: 1080px;
                height: 400px;
                display: grid;
                grid-template-columns: 662px 1fr;
            }

            div#board_left {
                margin: 0;
                padding: 0;
                width: 650px;
                background: white;
                border: 1px solid #d1d8e4;
                display: block;
                grid-template-rows: 400px;
                overflow-y:scroll;
            }

            div#board_right {
                padding: 0;
                background: white;
                border: 1px solid #d1d8e4;
            }

            div#server {
                margin: 0 auto;
                margin-top: 10px;
                width: 1080px;
                height: 400px;
                display: grid;
                grid-template-columns: 1fr;
            }

            div#server_left {
                margin: 0;
                padding: 0;
                background: white;
                border: 1px solid #d1d8e4;
                display: block;
                grid-template-rows: 400px;
                overflow-y:scroll;
            }

            div#bottom {
                margin: 0 auto;
                margin-top: 10px;
                width: 1080px;
                height: 100px;
                display: block;
                text-align: center;
                float: bottom;
                background: white;
                border: 1px solid #d1d8e4;
            }

            table#user_table, table#session_table, table#block_table, table#board_table, table#server_table{
                width: 100%;
                text-align: center;
            }
            div#board_rb {
                text-align: center;
            }

            div::-webkit-scrollbar {
                display: none;
            }

        </style>
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paper.js/0.9.25/paper-full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
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
                get_user_list();
                get_session_list();
                get_block_list();
                get_board_list();
                get_server_list();
                get_last_login();
            });
            $(document).on("click", ".btn_board_view", function(){
                var checkBtn = $(this);
                var tr = checkBtn.parent().parent();
                var td = tr.children();
                var no = td.eq(0).text();
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_board_view&board_num=" + no,
                    dataType: 'json',
                    success : function(data){
                        $('#board_num').text(data.board_num);
                        $('#board_title').text(data.board_title);
                        $('#board_user_id').text(data.board_user_id);
                        $('#board_content').text(data.board_content);
                        $('#board_date').text(data.board_date);
                    }
                });
            });
            $(document).on("click", "#btn_confirm", function(){
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_board_update&board_num=" + $('#board_num').text(),
                    success : function(data){
                        alert('메일 전송 완료!');
                        get_board_list();
                        $('#board_num').text('번호');
                        $('#board_title').text('제목');
                        $('#board_user_id').text('글쓴이');
                        $('#board_content').text('내용');
                        $('#board_date').text('날짜');
                    }
                });
            });
            function get_board_list(event) {
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_board_list",
                    success : function(data){
                        $("#admin_board_list_result").html(data);
                    }
                });
            }
            function get_server_list(event) {
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_server_list",
                    success : function(data){
                        $("#admin_server_list_result").html(data);
                    }
                });
            }
            function get_user_list(event) {
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_user_list",
                    success : function(data){
                        $("#admin_user_list_result").html(data);
                    }
                });
            }
            function get_session_list(event) {
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_session_list",
                    success : function(data){
                        $("#admin_session_list_result").html(data);
                    }
                });
            }
            function get_block_list(event) {
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_block_list",
                    success : function(data){
                        $("#admin_block_list_result").html(data);
                    }
                });
            }
            function get_last_login(event) {
                $.ajax({
                    type:"GET",
                    url: "/adminAjax.php?m=admin_last_login&user_id=admin",
                    success : function(data){
                        $("#last_login").html(data);
                    }
                });
            }
            $(function() {
                $.contextMenu({
                    selector: '.context-menu-one',
                    callback: function(key, options) {
                        var tr = $(this);
                        var td = tr.children();
                        var ip_addr = td.eq(0).text();
                        var mac_addr = td.eq(1).text();
                        $.ajax({
                            type:"GET",
                            url: "/adminAjax.php?m=admin_menu_block&ip_addr="+ip_addr+"&mac_addr="+mac_addr,
                            success : function(data){
                                alert(data);
                                get_block_list();
                            }
                        });
                    },
                    items: {
                        "ip_block": {name: "IP 차단", icon: ""},
                        "mac_block": {name: "MAC 차단", icon: ""},
                        "sep1": "---------",
                        "quit": {name: "종료", icon: ""}
                    }
                });
                $('.context-menu-one').on('click', function(e){
                    console.log('clicked', this);
                });
            });
            $(function() {
                $.contextMenu({
                    selector: '.context-menu-two',
                    callback: function(key, options) {
                        var tr = $(this);
                        var td = tr.children();
                        var ip_addr = td.eq(0).text();
                        var mac_addr = td.eq(1).text();
                        $.ajax({
                            type:"GET",
                            url: "/adminAjax.php?m=admin_menu_unblock&ip_addr="+ip_addr+"&mac_addr="+mac_addr,
                            success : function(data){
                                alert(data);
                                get_block_list();
                            }
                        });
                    },
                    items: {
                        "ip_unblock": {name: "IP 차단 해제", icon: ""},
                        "mac_unblock": {name: "MAC 차단 해제", icon: ""},
                        "sep1": "---------",
                        "quit": {name: "종료", icon: ""}
                    }
                });
                $('.context-menu-two').on('click', function(e){
                    console.log('clicked', this);
                });
            });
            $(function() {
                $.contextMenu({
                    selector: '.context-menu-three',
                    callback: function(key, options) {
                        var tr = $(this);
                        var td = tr.children();
                        var ip_addr = td.eq(0).text();
                        var mac_addr = td.eq(1).text();
                        $.ajax({
                            type:"GET",
                            url: "/adminAjax.php?m=admin_menu_unblock&ip_addr="+ip_addr+"&mac_addr="+mac_addr,
                            success : function(data){
                                alert(data);
                                get_block_list();
                            }
                        });
                    },
                    items: {
                        "user_info": {name: "사용자 정보", icon: ""},
                        "ip_unblock": {name: "IP 차단 해제", icon: ""},
                        "mac_unblock": {name: "MAC 차단 해제", icon: ""},
                        "sep1": "---------",
                        "quit": {name: "종료", icon: ""}
                    }
                });
                $('.context-menu-three').on('click', function(e){
                    console.log('clicked', this);
                });
            });
        </script>
    </head>
    <body>
        <div id="top">
            <div id="top_icon">
                <a href="admin.php"><img src="image/home_icon.png" style="height: 100px;"></a>
            </div>
            <h1 id="top_h1">Smart Car Admin</h1>
        </div>
        <div id="last_login">

        </div>
        <div id="session">
            <div id="session_left">
                <h3 style='text-align:center'>전체 사용자</h3>
                <div id='admin_user_list_result'></div>
            </div>
            <div id="session_middle">
                <h3 style='text-align:center'>현재 접속자</h3>
                <div id='admin_session_list_result'></div>
            </div>
            <div id="session_right">
                <h3 style='text-align:center'>차단 IP / MAC </h3>
                <div id='admin_block_list_result'></div>
            </div>
        </div>
        <div id="board">
            <div id="board_left">
                <h3 style='text-align:center'>문의 게시판</h3>
                <div id="admin_board_list_result"></div>
            </div>
            <div id="board_right">
                <p style='display:none' id='board_num'>번호</p>
                <h3 style='text-align:center' id='board_title'>제목</h3>
                <h4 style='text-align:right; margin: 0 10px' id='board_date'>날짜</h4>
                <h4 style='text-align:right; margin: 0 10px' id='board_user_id'>아이디</h4>
                <p style='margin:10px' id='board_content'>내용</p>
                <div id="board_rb">
                    <button type="button" id="btn_confirm">확인</button>
                </div>
            </div>
        </div>

        <div id="server">
            <div id="server_left">
                <h3 style='text-align:center'>로그</h3>
                <div id="admin_server_list_result"></div>
            </div>
        </div>

        <div id="bottom">
            bottom
        </div>
    </body>
</html>
