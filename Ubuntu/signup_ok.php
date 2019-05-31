<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');

    $user_id = $_POST['user_id'];
    $user_pw = $_POST['user_pw'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $car_ip = $_POST['car_ip'];
    $car_name = $_POST['car_name'];

    $user_id = stripslashes($user_id);
    $user_pw = stripslashes($user_pw);
    $user_name = stripslashes($user_name);
    $user_email = stripslashes($user_email);
    $car_ip = stripslashes($car_ip);
    $car_name = stripslashes($car_name);

    $user_id = mysqli_real_escape_string($con, $user_id);
    $user_pw = mysqli_real_escape_string($con, $user_pw);
    $user_name = mysqli_real_escape_string($con, $user_name);
    $user_email = mysqli_real_escape_string($con, $user_email);
    $car_ip = mysqli_real_escape_string($con, $car_ip);
    $car_name = mysqli_real_escape_string($con, $car_name);

    $user_pw = md5($user_pw);

    $sql1 = "insert into user (user_id, user_pw, user_name, user_email) values ('$user_id', '$user_pw', '$user_name', '$user_email')";
    $result_1 = mysqli_query($con, $sql1);


    $sql2 = "insert into car (car_ip, car_name, user_id) values ('$car_ip', '$car_name', '$user_id')";
    $result_2 = mysqli_query($con, $sql2);

    if($result_1 && $result_2){
        echo "회원 가입 완료";
    } else {
        echo "회원 가입 실패";
    }
    mysqli_free_result($result_1);
    mysqli_free_result($result_2);
    mysqli_close($con);
?>
<meta http-equiv='refresh' content='3;url=index.php'>
