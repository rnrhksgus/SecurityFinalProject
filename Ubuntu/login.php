<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.js"></script>
    </head>
    <body>
        <div data-role="header">
            <p>로그인</p>
        </div>
        <div data-role="content">
            <form action="login_ok.php" method="post" data-ajax="false">
                <p>ID : <input type="text" id="user_id" name="user_id" value=""></p>
                <p>PW : <input type="password" id="user_pw" name="user_pw" value=""></p>
                <input type="submit" name="" value="login">
            </form>
            <button onclick="location.href='signup.php'">sign up</button>
        </div>
    </body>
</html>
