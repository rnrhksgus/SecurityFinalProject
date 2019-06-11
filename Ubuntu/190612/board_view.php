<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.js"></script>
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
        <style>
            .error_messge {
                margin: 0;
                margin-bottom: 5px;
                border: 0;
                padding: 0;
                color: red;
                display: none;
            }
            a {
                text-decoration: none;
            }

        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                var board_num = getParameterByName('board_num');
                if (board_num == ""){
                    alert('게시물을 확인해 주세요');
                    return false;
                }
                $.ajax({
                    type:"GET",
                    url: "/joinAjax.php?m=board_view&board_num=" + board_num,
                    dataType: 'json',
                    success : function(data){
                        $('#board_title').val(data.board_title);
                        $('#board_user_id').val(data.board_user_id);
                        $('#board_content_textarea').val(data.board_content);
                    }
                });
            });
            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }
        </script>
    </head>
    <body>
        <div data-role="header">
            <h1 style="text-align:center;">게시글 보기</h1>
        </div>
        <div data-role="content">
            <label for="board_title"><b>제목</b></label>
            <input type="text" name="board_title" id="board_title" readonly />
            <label for="board_user_id"><b>아이디</b></label>
            <input type="text" name="board_user_id" id="board_user_id" readonly />
            <label for="board_content"><b>내용</b></label>
            <textarea name="board_content_textarea" id="board_content_textarea" readonly></textarea>
            <input type="hidden" name="board_content" id="board_content">
            <button type="button" name="button" id="btn_board_modify" >수정</button>
            <button type="button" name="button" id="btn_board_delete">삭제</button>
            <button onclick="location.href='board.php'">취소</button>
        </div>
    </body>
</html>
