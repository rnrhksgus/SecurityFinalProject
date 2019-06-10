<!DOCTYPE html>
<?php
    if(empty($_POST['user_id']) || empty($_POST['user_pw'])){
        echo "<meta http-equiv='refresh' content='0;url=login.php'>";
        exit;
    }
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');

    $user_id = $_POST['user_id'];
    $user_pw = $_POST['user_pw'];

    $user_id = stripslashes($user_id);
    $user_pw = stripslashes($user_pw);

    $user_id = mysqli_real_escape_string($con, $user_id);
    $user_pw = mysqli_real_escape_string($con, $user_pw);

    $user_pw = md5($user_pw);

    $sql = "select user_login_fail from user where user_id = '$user_id'";
    $result = mysqli_query($con, $sql);
    if(mysqli_fetch_object($result)->user_login_fail < 5){
        $sql = "select * from user where user_id = '$user_id' and user_pw = '$user_pw'";
        $result = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_object($result)){
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $row->user_name;
            $sql = "update user set user_login_fail = 0 where user_id = '$user_id'";
            mysqli_query($con, $sql);
        } else {
            $sql = "update user set user_login_fail = user_login_fail + 1 where user_id = '$user_id'";
            mysqli_query($con, $sql);
        }
    }
    mysqli_free_result($result);
    mysqli_close($con);
?>
<meta http-equiv='refresh' content='0;url=index.php'>

