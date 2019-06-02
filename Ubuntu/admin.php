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
            <h1 style="text-align:center;">관리자 페이지</h1>
        </div>
        <div data-role="content">
            <?php
                echo $_SERVER["REMOTE_ADDR"];
            ?>
        </div>
    </body>
</html>
