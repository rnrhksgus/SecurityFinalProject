<!DOCTYPE html>
<meta charset="utf-8" />
<?php
    session_start();
    if(!isset($_SESSION['user_id'])) {
        echo "<meta http-equiv='refresh' content='0;url=login.php'>";
        exit;
    }
    $user_id = $_SESSION['user_id'];
    echo "<p>안녕하세요. $user_id 님</p>";
    echo "<p><a href='logout.php'>로그아웃</a></p>";
?>
