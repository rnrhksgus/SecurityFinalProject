<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');

    $board_title = $_POST['board_title'];
    $board_user_id = $_POST['board_user_id'];
    $board_pw = $_POST['board_pw'];
    $board_content = $_POST['board_content'];

    $board_title = stripslashes($board_title);
    $board_user_id = stripslashes($board_user_id);
    $board_pw = stripslashes($board_pw);
    $board_content = stripslashes($board_content);

    $board_title = mysqli_real_escape_string($con, $board_title);
    $board_user_id = mysqli_real_escape_string($con, $board_user_id);
    $board_pw = mysqli_real_escape_string($con, $board_pw);
    $board_content = mysqli_real_escape_string($con, $board_content);

    $board_pw = md5($board_pw);

    $sql = "select sequence_value from sequence where sequence_name = 'board'";
    $result = mysqli_query($con, $sql);
    $board_num = (int)mysqli_fetch_object($result)->sequence_value;

    $sql = "insert into board values ($board_num, '$board_title', '$board_pw', '$board_user_id', now(), '$board_content', 0)";
    $result = mysqli_query($con, $sql);

    $sql = "update sequence set sequence_value = sequence_value + 1 where sequence_name = 'board'";
    $result = mysqli_query($con, $sql);

    mysqli_free_result($result);
    mysqli_close($con);
?>
<meta http-equiv='refresh' content='0;url=board.php'>
