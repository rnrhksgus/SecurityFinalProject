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
                                location.href="testindex.php";
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
            <button onclick="location.href='testsignup.php'">회원가입</button>
            <button onclick="location.href='board.php'">문의하기</button>
        </div>
    </body>
</html>

