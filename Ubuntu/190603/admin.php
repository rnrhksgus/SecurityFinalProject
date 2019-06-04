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
            $indicesServer = array(
	    'PHP_SELF',
            'REMOTE_ADDR',
            'REMOTE_PORT',
            'REMOTE_USER',
            'SCRIPT_FILENAME',
            'SCRIPT_NAME',
            'REQUEST_URI');

            echo '<table cellpadding="10">' ;
            foreach ($indicesServer as $arg) {
                if (isset($_SERVER[$arg])) {
                    echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
                }
                else {
                    echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
                }
            }
            exec("arp -H ether -n -a ".$REMOTE_ADDR."",$values);
            $parts = explode(' ',$values[0]);
            echo "";
            echo "<tr><td>MAC</td><td>$parts[3]</td></tr>";
            echo '</table>' ;
            ?>
        </div>
    </body>
</html>

