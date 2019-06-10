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
                $sql = "insert into server_log values ('$php_self', '$remote_addr', '$mac_addr', '$remote_port', '$script_name','$request_uri', now())";
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
        </style>
        <script type="text/javascript">
            flag_board_title = false;
            flag_board_user_id = false;
            flag_board_pw = false;
            flag_board_content = false;
            $(document).ready(function(){
                $("#board_title").blur(function(){
                    checkBoardTitle();
                });
                $("#board_user_id").blur(function(){
                    checkBoardUserID();
                });
                $("#board_pw").blur(function(){
                    checkBoardPassword();
                });
                $("#board_content_textarea").blur(function(){
                    checkBoardContent();
                    $('#board_content').val($("#board_content_textarea").val());
                });
                $("#btn_board_write").click(function(){
                    if(flag_board_title && flag_board_user_id && flag_board_pw && flag_board_content){
                        $("#form_board_write").submit();
                    } else {
                        if(!flag_board_title){
                            $("#board_title").focus();
                        } else if (!flag_board_user_id){
                            $("#board_user_id").focus();
                        } else if (!flag_board_pw){
                            $("#board_pw").focus();
                        } else if (!flag_board_content){
                            $("#board_content_textarea").focus();
                        }
                        return false;
                    }
                });
            });
            function checkBoardTitle(event) {
                var board_title = $("#board_title").val();
                var eMsg = $("#error_messge_board_title");
                if(board_title == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_board_title = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                flag_board_title = true;
                return true;
            }
            function checkBoardUserID(event) {
                var board_user_id = $("#board_user_id").val();
                var eMsg = $("#error_messge_board_user_id");
                if(board_user_id == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_board_user_id = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                $.ajax({
                    type:"GET",
                    url: "/joinAjax.php?m=checkId&user_id=" + board_user_id,
                    success : function(data){
                        var result = data.replace(/\n/g, "")
                        if (result == "N"){
                            showErrorMsg(eMsg, "회원가입이 안된 아이디이거나 아이디를 확인해주세요.");
                            flag_board_user_id = false;
                            return false;
                        } else {
                            showSuccessMsg(eMsg, "등록된 아이디 입니다.");
                        }
                    }
                });
                flag_board_user_id = true;
                return true;
            }
            function checkBoardPassword(event) {
                var board_pw = $("#board_pw").val();
                var eMsg = $("#error_messge_board_pw");
                if(board_pw == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_board_pw = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                flag_board_pw = true;
                return true;
            }
            function checkBoardContent(event) {
                var board_content = $("#board_content_textarea").val();
                var eMsg = $("#error_messge_board_content");
                if(board_content == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_board_content = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                flag_board_content = true;
                return true;
            }
            function showErrorMsg(tar, msg){
                tar.text(msg);
                tar.css("color", "red");
                tar.css("display", "block");
            }
            function hiddenErrorMsg(tar){
                tar.css("display", "none");
            }
            function showSuccessMsg(tar, msg){
                tar.text(msg);
                tar.css("color", "green");
                tar.css("display", "block");
            }
        </script>
    </head>
    <body>
        <div data-role="header">
            <h1 style="text-align:center;">게시글 작성</h1>
        </div>
        <div data-role="content">
            <form id="form_board_write" action="board_write_ok.php" method="post" data-ajax="false">
                <label for="board_title"><b>제목</b></label>
                <input type="text" name="board_title" id="board_title" />
                <p class="error_messge" id="error_messge_board_title">필수 정보 입니다.</p>
                <label for="board_user_id"><b>아이디</b></label>
                <input type="text" name="board_user_id" id="board_user_id" />
                <p class="error_messge" id="error_messge_board_user_id">필수 정보 입니다.</p>
                <label for="board_pw"><b>비밀번호</b></label>
                <input type="password" name="board_pw" id="board_pw" maxlength="20" />
                <p class="error_messge" id="error_messge_board_pw">필수 정보 입니다.</p>
                <label for="pw"><b>내용</b></label>
                <textarea name="board_content_textarea" id="board_content_textarea"></textarea>
                <input type="hidden" name="board_content" id="board_content">
                <p class="error_messge" id="error_messge_board_content">필수 정보 입니다.</p>
                <button type="button" name="button" id="btn_board_write">문의하기</button>
            </form>
            <button onclick="location.href='board.php'">취소</button>
        </div>
    </body>
</html>

