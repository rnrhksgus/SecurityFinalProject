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
            table {
                width: 100%;
            }
            table, th, td {
                text-align: center;
                font-size: 18px;
            }
            table {
                border: 1px solid black;
                border-bottom: 2px solid black;
                border-right: 2px solid black;
            }
            th {
                border-bottom: 1px double black;
            }
            td {
                height: 40px;
            }
            div {
                text-align: center;
            }
            a {
                text-decoration: none;
                color: black;
            }
            .a_bottom {
                margin: 10px;
                font-size: 18px;
            }
            #page_span {
                margin: 10px;
                font-size: 20px;
                font-weight: bold;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                var page = getParameterByName('page');
                if (page == ""){
                    page = 1;
                }
                $.ajax({
                    type:"GET",
                    url: "/joinAjax.php?m=board_list&page=" + page,
                    success : function(data){
                        $("#result").html(data);
                    }
                });
            });
            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            $(document).on("click", ".check_pw", function(){

              var tr = $(this).parent().parent();
              var td = tr.children();
              var num = td.eq(0).text();
              // alert(num);
              // return false;
              var name_value = prompt("PASSWORD 를 입력하세요", "");
              $.ajax({
                    type:"GET",
                    url: "/joinAjax.php?m=board_pw_check&board_num="+num+"&board_pw="+name_value,
                    success : function(data){

                      if(data=='Y')
                      {

                        location.href="board_view.php?board_num="+num;
                      }else if(data=="N")
                      {
                        alert("비밀번호를 확인해주세요.!")
                      }

                    }
                });

                  return false;

            });

        </script>
    </head>
    <body>
        <div data-role="header">
            <h1 style="text-align:center;">문의하기</h1>
        </div>
        <div data-role="content">
            <div id="result"></div>
            <button onclick="location.href='board_write.php'">글쓰기</button>
            <button onclick="location.href='login.php'">로그인페이지</button>
        </div>
    </body>
</html>
