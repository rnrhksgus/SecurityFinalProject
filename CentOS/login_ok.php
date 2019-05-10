
<?php
    if(!isset($_POST['user_id']) || !isset($_POST['user_pw']))
        exit;
    $user_id =  $_POST[user_id];
    $user_pw =  $_POST[user_pw];
    $conn = mysql_connect('localhost', 'user', '123456');
    $db = mysql_select_db('user_db', $conn);
    $sql = "select * from user where user_id = '$_POST[user_id]'";
    $sql_result = mysql_query($sql, $conn);
    $count = mysql_num_rows($sql_result);
    $db_user_id = mysql_result($sql_result, 0, user_id);
    $db_user_pw = mysql_result($sql_result, 0, user_pw);
    if($db_user_pw == $_POST[user_pw])
        session_start();
        $_SESSION['user_id'] = $user_id;
?>
<meta http-equiv='refresh' content='0;url=index.php'>
