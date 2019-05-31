<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');
    $var = $_GET['m'];
    if ($var == "checkId"){
        $user_id = $_GET['user_id'];
        $user_id = stripslashes($user_id);
        $user_id = mysqli_real_escape_string($con, $user_id);
        $sql = "select count(*) as cnt from user where user_id = '$user_id'";
        $result = mysqli_query($con, $sql);
        if (mysqli_fetch_object($result)->cnt == 0) {
            echo "N";
        } else {
            echo "Y";
        }
        mysqli_free_result($result);
    } else if ($var == "checkCarIP"){
        $car_ip = $_GET['car_ip'];
        $car_ip = stripslashes($car_ip);
        $car_ip = mysqli_real_escape_string($con, $car_ip);
        $sql = "select count(*) as cnt from car where car_ip = '$car_ip'";
        $result = mysqli_query($con, $sql);
        if (mysqli_fetch_object($result)->cnt == 0) {
            $sql = "select count(*) as cnt from car_auth where car_ip = '$car_ip'";
            $result = mysqli_query($con, $sql);
            if (mysqli_fetch_object($result)->cnt == 1) {
                echo "N";
            } else {
                echo "Y";
            }
        } else {
            echo "Y";
        }
        mysqli_free_result($result);
    } else if ($var == "checkCarPin"){
        $car_ip = $_GET['car_ip'];
        $car_pin = $_GET['car_pin'];
        $car_ip = stripslashes($car_ip);
        $car_pin = stripslashes($car_pin);
        $car_ip = mysqli_real_escape_string($con, $car_ip);
        $car_pin = mysqli_real_escape_string($con, $car_pin);
        $sql = "select count(*) as cnt from car_auth where car_ip = '$car_ip' and car_pin = '$car_pin'";
        $result = mysqli_query($con, $sql);
        if (mysqli_fetch_object($result)->cnt == 0) {
            echo "Y";
        } else {
            echo "N";
        }
        mysqli_free_result($result);
    }
    mysqli_close($con);
?>
