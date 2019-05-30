<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');

    $user_id = $_POST['user_id'];
    $user_pw = $_POST['user_pw'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];

    $user_id = stripslashes($user_id);
    $user_pw = stripslashes($user_pw);
    $user_name = stripslashes($user_name);
    $user_email = stripslashes($user_email);

    $user_id = mysqli_real_escape_string($con, $user_id);
    $user_pw = mysqli_real_escape_string($con, $user_pw);
    $user_name = mysqli_real_escape_string($con, $user_name);
    $user_email = mysqli_real_escape_string($con, $user_email);

    $user_pw = md5($user_pw);

    $sql = "insert into user (user_id, user_pw, user_name, user_email) values ('$user_id', '$user_pw', '$user_name', '$user_email')";
    $result = mysqli_query($con, $sql);

    if($result){
        echo "회원 가입 완료";
    } else {
        echo "회원 가입 실패";
    }
    mysqli_free_result($result);
    mysqli_close($con);
?>
<meta http-equiv='refresh' content='5;url=index.php'>
