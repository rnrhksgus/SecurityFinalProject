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
    </head>

    <body>
        <div data-role="header">
            <h1 style="text-align:center;">관리자 페이지</h1>
        </div>
        <div data-role="content">
            <h1>Admin Page</h1>

        </div>
    </body>
</html>

