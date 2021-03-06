<!DOCTYPE html>
<html>
<?php
    session_start();
    if(isset($_SESSION['user_id'])) {
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
        exit;
    }
?>
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

                $("#btn_login").click(function(){
                    var id = $("#user_id").val();
                    var pw = $("#user_pw").val();
                    if(id == ""){
                        $("#user_id").focus();
                        return false;
                    }
                    if(pw == ""){
                        $("#user_pw").focus();
                        return false;
                    }
                    var loginData = $('#form_login').serialize();
                    $.ajax({
                        type:"POST",
                        url: "/postAjax.php",
                        data: loginData,
                        success : function(data){
                            var result = data.replace(/\n/g, "");
                            if(result == "Y"){
                                alert("로그인 성공");
                                location.href="index.php";
                            } else if(result == "N3"){
                                alert("현재 접속중입니다.");
                                location.href="login.php";
                                return false;
                            } else if(result == "N2"){
                                alert("아이디와 비밀번호를 확인해 주세요");
                                location.href="login.php";
                                return false;
                            } else if(result == "N1"){
                                alert("비밀번호 5회 이상 오류! 문의해주세요");
                                location.href="login.php";
                                return false;
                            } else {
                                alert("오류");
                                location.href="login.php";
                                return false;
                            }
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        <div data-role="header">
            <h1 style="text-align:center;">로그인</h1>
        </div>
        <div data-role="content">
            <form id="form_login">
                <input type="hidden" name="typeAjax" value="user_login">
                <p>ID : <input type="text" id="user_id" name="user_id" value=""></p>
                <p>PW : <input type="password" id="user_pw" name="user_pw" value=""></p>
                <button type="button" name="button" id="btn_login">로그인</button>
            </form>
            <button onclick="location.href='signup.php'">회원가입</button>
            <button onclick="location.href='board.php'">문의하기</button>
        </div>
    </body>
</html>

