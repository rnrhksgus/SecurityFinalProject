<?php
    session_start();
    $user_id = $_SESSION['user_id'];
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');
    $sql = "delete from session where session_user_id='$user_id'";
    $result = mysqli_query($con, $sql);
    mysqli_free_result($result);
    mysqli_close($con);
    session_destroy();
?>
<meta http-equiv='refresh' content='0;url=login.php'>

