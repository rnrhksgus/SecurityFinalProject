<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');
    $var = $_GET['m'];
    switch($var){
        case "checkId" :
            $user_id = $_GET['user_id'];
            $user_id = stripslashes($user_id);
            $user_id = mysqli_real_escape_string($con, $user_id);
            $sql = "select count(*) as cnt from user where user_id = '$user_id'";
            $result = mysqli_query($con, $sql);
            if (mysqli_fetch_object($result)->cnt == 0) {
                echo "Y";
            } else {
                echo "N";
            }
            mysqli_free_result($result);
            break;

        default :
            echo "default";
    }
    mysqli_close($con);
?>
