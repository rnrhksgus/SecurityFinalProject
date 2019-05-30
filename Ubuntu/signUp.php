<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.js"></script>
    </head>
    <body>
        <div data-role="header">
            <p>회원가입</p>
        </div>
        <div data-role="content">
            <form action="signup_ok.php" method="post" data-ajax="false">
                <label for="id">ID</label>
                <input type="text" name="user_id" />
                <label for="pw">PW</label>
                <input type="password" name="user_pw" />
                <label for="pwc">pwc</label>
                <input type="password" name="user_pwc" />
                <label for="name">Name</label>
                <input type="text" name="user_name" />
                <label for="email">E-mail</label>
                <input type="text" name="user_email" />
                <input type="submit" name="" value="submit">
            </form>
        </div>
    </body>
</html>
