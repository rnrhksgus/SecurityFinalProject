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
            var flag_id = false;
            var flag_pw = false;
            var flag_pwc = false;
            var flag_name = false;
            var flag_email = false;
            var flag_car_ip = false;
            var flag_car_name = false;
            var flag_car_pin = false;

            $(document).ready(function(){
                $("#user_id").blur(function(){
                    checkId();
                });
                $("#user_pw").blur(function(){
                    checkPassword();
                });
                $("#user_pwc").blur(function(){
                    checkPasswordCheck();
                });
                $("#user_name").blur(function(){
                    checkName();
                });
                $("#user_email").blur(function(){
                    checkEmail();
                });
                $("#car_ip").blur(function(){
                    checkCarIP();
                });
                $("#car_name").blur(function(){
                    checkCarName();
                });
                $("#car_pin").blur(function(){
                    checkCarPin();
                });
                $("#btn_join").click(function(){
                    if(flag_id && flag_pw && flag_pwc && flag_name && flag_email && flag_car_ip && flag_car_name && flag_car_pin){
                        $("#form_join").submit();
                    } else {
                        if(!flag_id){
                            $("#user_id").focus();
                        } else if (!flag_pw){
                            $("#user_pw").focus();
                        } else if (!flag_pwc){
                            $("#user_pwc").focus();
                        } else if (!flag_name){
                            $("#user_name").focus();
                        } else if (!flag_email){
                            $("#user_email").focus();
                        } else if (!flag_car_ip){
                            $("#car_ip").focus();
                        } else if (!flag_car_name){
                            $("#car_name").focus();
                        } else if (!flag_car_pin){
                            $("#car_pin").focus();
                        }
                        return false;
                    }
                });
            });
            function checkId(event) {
                var id = $("#user_id").val();
                var eMsg = $("#error_messge_id");
                if(id == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_id = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                var isID = /^[a-z0-9][a-z0-9_\-]{4,19}$/;
                if (!isID.test(id)) {
                    showErrorMsg(eMsg, "5~20자의 영문 소문자, 숫자와 특수기호(_),(-)만 사용 가능합니다.");
                    flag_id = false;
                    return false;
                }
                $.ajax({
                    type:"GET",
                    url: "/joinAjax.php?m=checkId&user_id=" + id,
                    success : function(data){
                        var result = data.replace(/\n/g, "")
                        if (result == "Y"){
                            showErrorMsg(eMsg, "이미 사용중이거나 탈퇴한 아이디 입니다.");
                            flag_id = false;
                            return false;
                        } else {
                            showSuccessMsg(eMsg, "멋진 아이디 입니다.");
                        }
                    }
                });
                flag_id = true;
                return true;
            }
            function checkPassword(event) {
                var pw = $("#user_pw").val();
                var eMsg = $("#error_messge_pw");
                if(pw == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_pw = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                var pattern1 = /[0-9]/;
                var pattern2 = /[a-zA-Z]/;
                var pattern3 = /[~!@\#$%<>^&*]/;

                if(!pattern1.test(pw)||!pattern2.test(pw)||!pattern3.test(pw)||pw.length<8||pw.length>50){
                    showErrorMsg(eMsg,"8~16자 영문 대 소문자, 숫자, 특수문자를 사용하세요.");
                    flag_pw = false;
                    return false;
                }
                flag_pw = true;
                return true;
            }
            function checkPasswordCheck(event) {
                var pw = $("#user_pw").val();
                var pwc = $("#user_pwc").val();
                var eMsg = $("#error_messge_pwc");
                if(pwc == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_pwc = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                if (flag_pw == true){
                    if (pw == pwc){
                        showSuccessMsg(eMsg, "비밀번호가 일치합니다.");
                    } else {
                        showErrorMsg(eMsg, "비밀번호가 일치하지 않습니다.");
                        flag_pwc = false;
                        return false;
                    }
                } else {
                    showErrorMsg(eMsg, "비밀번호 먼저 확인하여 주세요");
                    flag_pwc = false;
                }
                flag_pwc = true;
                return true;
            }
            function checkName(event) {
                var name = $("#user_name").val();
                var eMsg = $("#error_messge_name");
                if(name == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_name = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                flag_name = true;
                return true;
            }
            function checkEmail(event) {
                var email = $("#user_email").val();
                var eMsg = $("#error_messge_email");
                if(email == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_email = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                var isemail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (!isemail.test(email)) {
                    showErrorMsg(eMsg, "올바른 이메일을 입력해 주세요.");
                    flag_email = false;
                    return false;
                } else {
                    showSuccessMsg(eMsg, "올바른 이메일을 입력하였습니다.");
                }
                flag_email = true;
                return true;
            }

            function checkCarIP(event) {
                var car_ip = $("#car_ip").val();
                var eMsg = $("#error_messge_car_ip");
                if(car_ip == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_car_ip = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }

                isIP = /^(1|2)?\d?\d([.](1|2)?\d?\d){3}$/;
                if (!isIP.test(car_ip)) {
                    showErrorMsg(eMsg, "올바른 IP를 입력해 주세요.");
                    flag_car_ip = false;
                    return false;
                } else {
                    $.ajax({
                        type:"GET",
                        url: "/joinAjax.php?m=checkCarIP&car_ip=" + car_ip,
                        success : function(data){
                            var result = data.replace(/\n/g, "")
                            if (result == "Y"){
                                showErrorMsg(eMsg, "차량 IP가 맞지 않거나 이미 사용중입니다.");
                                flag_car_ip = false;
                                return false;
                            } else {
                                showSuccessMsg(eMsg, "등록 가능한 차량 IP입니다.");
                            }
                        }
                    });
                }
                flag_car_ip = true;
                return true;
            }
            function checkCarName(event) {
                var car_name = $("#car_name").val();
                var eMsg = $("#error_messge_car_name");
                if(car_name == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_car_name = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                flag_car_name = true;
                return true;
            }
            function checkCarPin(event) {
                var car_ip = $("#car_ip").val();
                var car_pin = $("#car_pin").val();
                var eMsg = $("#error_messge_car_pin");
                if(car_pin == ""){
                    showErrorMsg(eMsg, "필수 정보 입니다.");
                    flag_car_pin = false;
                    return false;
                } else {
                    hiddenErrorMsg(eMsg);
                }
                if (flag_car_ip){
                    $.ajax({
                        type:"GET",
                        url: "/joinAjax.php?m=checkCarPin&car_ip=" + car_ip + "&car_pin=" + car_pin,
                        success : function(data){
                            var result = data.replace(/\n/g, "")
                            if (result == "Y"){
                                showErrorMsg(eMsg, "PIN번호를 확인해 주세요");
                                flag_car_pin = false;
                                return false;
                            } else {
                                showSuccessMsg(eMsg, "차량 PIN번호 인증에 성공하셨습니다.");
                            }
                        }
                    });
                } else {
                    showErrorMsg(eMsg, "차량 IP를 확인해 주세요");
                    flag_car_pin = false;
                    return false;
                }
                flag_car_pin = true;
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
            <h1 style="text-align:center;">회원 가입 및 차량 등록</h1>
        </div>
        <div data-role="content">
            <form id="form_join" action="signup_ok.php" method="post" data-ajax="false">
                <label for="id"><b>아이디</b></label>
                <input type="text" name="user_id" id="user_id" maxlength="20" />
                <p class="error_messge" id="error_messge_id">필수 정보 입니다.</p>
                <label for="pw"><b>비밀번호</b></label>
                <input type="password" name="user_pw" id="user_pw" maxlength="20" />
                <p class="error_messge" id="error_messge_pw">필수 정보 입니다.</p>
                <label for="pwc"><b>비밀번호 재확인</b></label>
                <input type="password" name="user_pwc" id="user_pwc" maxlength="20" />
                <p class="error_messge" id="error_messge_pwc">필수 정보 입니다.</p>
                <label for="name"><b>이름</b></label>
                <input type="text" name="user_name" id="user_name" />
                <p class="error_messge" id="error_messge_name">필수 정보 입니다.</p>
                <label for="email"><b>이메일</b></label>
                <input type="email" name="user_email" id="user_email" />
                <p class="error_messge" id="error_messge_email">필수 정보 입니다.</p>
                <label for="car_ip"><b>차량 IP</b></label>
                <input type="text" name="car_ip" id="car_ip" />
                <p class="error_messge" id="error_messge_car_ip">필수 정보 입니다.</p>
                <label for="car_ip"><b>차량 이름</b></label>
                <input type="text" name="car_name" id="car_name" />
                <p class="error_messge" id="error_messge_car_name">필수 정보 입니다.</p>
                <label for="car_"><b>차량 PIN</b></label>
                <input type="text" name="car_pin" id="car_pin" />
                <p class="error_messge" id="error_messge_car_pin">필수 정보 입니다.</p>
                <button type="button" name="button" id="btn_join">가입하기</button>
            </form>
            <button onclick="location.href='login.php'">로그인 페이지</button>
        </div>
    </body>
</html>

