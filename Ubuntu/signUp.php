<php
     $host = 'localhost';
     $user = 'user';
     $pw = '123456';
     $dbName = 'user_db';
     $mysqli = new mysqli($host, $user, $pw, $dbName);

     $user_id=$_POST['user_id'];
     $user_pw=($_POST['user_pw']);

     $user_name=$_POST['user_name'];
     $user_email=$_POST['user_email'];
     echo "$user_id<br>";
     echo "$user_pw<br>";
     echo "$user_name<br>";
     echo "$user_email<br>";
     $sql = "insert into user(user_id, user_pw, user_name, user_email)";
     $sql = $sql. "values('$user_id','$user_pw','$user_name','$user_email')";
     echo "$sql<br>";
     if($mysqli->query($sql)){
      echo '가입완료';
     }else{
      echo '가입실패';
    }
?>
<meta http-equiv='refresh' content='3;url=index.php'>

