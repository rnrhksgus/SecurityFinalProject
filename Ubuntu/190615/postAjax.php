<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');
    $var = $_POST['typeAjax'];
    $output = "";
    if($var == 'user_login'){
        $user_id = $_POST['user_id'];
        $user_id = stripslashes($user_id);
        $user_id = mysqli_real_escape_string($con, $user_id);
        $user_pw = $_POST['user_pw'];
        $user_pw = stripslashes($user_pw);
        $user_pw = mysqli_real_escape_string($con, $user_pw);
        $user_pw = md5($user_pw);
        $sql = "select user_login_fail from user where user_id = '$user_id'";
        $result = mysqli_query($con, $sql);
        if(mysqli_fetch_object($result)->user_login_fail < 5){
            $sql = "select * from user where user_id = '$user_id' and user_pw = '$user_pw'";
            $result = mysqli_query($con, $sql);
            if ($row = mysqli_fetch_object($result)){
                $sql = "select count(*) as cnt from session where session_user_id = '$user_id'";
                $result = mysqli_query($con, $sql);
                if(mysqli_fetch_object($result)->cnt == 0 ) {
                    session_start();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $row->user_name;
                    $_SESSION['user_type'] = $row->user_type;
                    $remote_addr = $_SERVER['REMOTE_ADDR'];
                    exec("arp -H ether -n -a ".$remote_addr."",$values);
                    $parts = explode(' ',$values[0]);
                    $mac_addr = $parts[3];
                    $_SESSION['ip_addr'] = $remote_addr;
                    $_SESSION['mac_addr'] = $mac_addr;
                    $sql = "select sequence_value from sequence where sequence_name = 'session'";
                    $result = mysqli_query($con, $sql);
                    $session_num = (int)mysqli_fetch_object($result)->sequence_value;
                    $sql = "insert into session (session_num, session_user_id, session_ip_addr, session_mac_addr) values ($session_num, '$user_id', '$remote_addr', '$mac_addr')";
                    mysqli_query($con, $sql);
                    $sql = "update user set user_ip_addr = '$remote_addr', user_mac_addr='$mac_addr' where user_id='$user_id'";
                    mysqli_query($con, $sql);
                    $sql = "update sequence set sequence_value = sequence_value + 1 where sequence_name = 'session'";
                    $result = mysqli_query($con, $sql);
                    $sql = "update user set user_date = now() where user_id = '$user_id' and user_pw = '$user_pw'";
                    mysqli_query($con, $sql);
                    $output = "Y";
                } else {
                    $output = "N3";
                }
                $sql = "update user set user_login_fail = 0 where user_id = '$user_id'";
                mysqli_query($con, $sql);
            } else {
                $output = "N2";
            }
        } else {
            $sql = "update user set user_login_fail = user_login_fail + 1 where user_id = '$user_id'";
            mysqli_query($con, $sql);
            $output = "N1";
        }
        mysqli_free_result($result);
    } else if ($var == 'user_signup'){
        $user_id = $_POST['user_id'];
        $user_id = stripslashes($user_id);
        $user_id = mysqli_real_escape_string($con, $user_id);

        $user_pw = $_POST['user_pw'];
        $user_pw = stripslashes($user_pw);
        $user_pw = mysqli_real_escape_string($con, $user_pw);
        $user_pw = md5($user_pw);

        $user_name = $_POST['user_name'];
        $user_name = stripslashes($user_name);
        $user_name = mysqli_real_escape_string($con, $user_name);

        $user_email = $_POST['user_email'];
        $user_email = stripslashes($user_email);
        $user_email = mysqli_real_escape_string($con, $user_email);

        $car_ip = $_POST['car_ip'];
        $car_ip = stripslashes($car_ip);
        $car_ip = mysqli_real_escape_string($con, $car_ip);

        $car_name = $_POST['car_name'];
        $car_name = stripslashes($car_name);
        $car_name = mysqli_real_escape_string($con, $car_name);

        $sql1 = "insert into user (user_id, user_pw, user_name, user_email) values ('$user_id', '$user_pw', '$user_name', '$user_email')";
        $result_1 = mysqli_query($con, $sql1);

        $sql2 = "insert into car (car_ip, car_name, user_id) values ('$car_ip', '$car_name', '$user_id')";
        $result_2 = mysqli_query($con, $sql2);

        if($result_1 && $result_2){
            $output = "Y";
        } else {
            $output = "N";
        }
        mysqli_free_result($result_1);
        mysqli_free_result($result_2);
    } else if ($var == 'board_write'){
        $board_title = $_POST['board_title'];
        $board_title = stripslashes($board_title);
        $board_title = mysqli_real_escape_string($con, $board_title);

        $board_user_id = $_POST['board_user_id'];
        $board_user_id = stripslashes($board_user_id);
        $board_user_id = mysqli_real_escape_string($con, $board_user_id);

        $board_pw = $_POST['board_pw'];
        $board_pw = stripslashes($board_pw);
        $board_pw = mysqli_real_escape_string($con, $board_pw);
        $board_pw = md5($board_pw);

        $board_content = $_POST['board_content'];
        $board_content = stripslashes($board_content);
        $board_content = mysqli_real_escape_string($con, $board_content);

        $sql = "select sequence_value from sequence where sequence_name = 'board'";
        $result = mysqli_query($con, $sql);
        $board_num = (int)mysqli_fetch_object($result)->sequence_value;
        $sql = "insert into board values ($board_num, '$board_title', '$board_pw', '$board_user_id', now(), '$board_content', 0)";
        $result = mysqli_query($con, $sql);

        $sql = "update sequence set sequence_value = sequence_value + 1 where sequence_name = 'board'";
        $result = mysqli_query($con, $sql);

        $output = "Y";
        mysqli_free_result($result);
    }
    echo "$output";
    mysqli_close($con);
?>
