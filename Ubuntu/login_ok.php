<!DOCTYPE html>
<?php
    if(empty($_POST['user_id']) || empty($_POST['user_pw'])){
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
        exit;
    }
    $user_id =  $_POST[user_id];
    $user_pw =  $_POST[user_pw];
    $conn = mysqli_connect('localhost', 'user', '123456', 'user_db');
    $sql = "select * from user where user_id = '$user_id'"; 
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_object($result)){
	if($user_pw == $row->user_pw){
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $row->user_name;
	}
    }
    mysqli_free_result($result);
    mysqli_close($conn);
?>
<meta http-equiv='refresh' content='0;url=index.php'>

