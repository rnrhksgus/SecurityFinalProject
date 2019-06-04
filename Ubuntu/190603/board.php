<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.2/jquery.mobile-1.1.2.min.js"></script>
        <style>
            table {
                width: 100%;
            }
            table, th, td {
                text-align: center;
                font-size: 18px;
            }
            table {
                border: 1px solid black;
                border-bottom: 2px solid black;
                border-right: 2px solid black;
            }
            th {
                border-bottom: 1px double black;
            }
            td {
                height: 40px;
            }
            div {
                text-align: center;
            }
	    a {
		text-decoration: none;
		color: black;
	    }
            .a_bottom {
                margin: 10px;
                font-size: 18px;
            }
            #page_span {
                margin: 10px;
                font-size: 20px;
                font-weight: bold;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                var page = getParameterByName('page');
                if (page == ""){
                    page = 1;
                }
                $.ajax({
                    type:"GET",
                    url: "/joinAjax.php?m=board_list&page=" + page,
                    success : function(data){
                        $("#result").html(data);
                    }
                });
            });
            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }
        </script>
    </head>
    <body>
        <div data-role="header">
            <h1 style="text-align:center;">문의하기</h1>
        </div>
        <div data-role="content">
            <div id="result"></div>
            <button onclick="location.href='board_write.php'">글쓰기</button>
            <button onclick="location.href='login.php'">로그인페이지</button>
        </div>
    </body>
</html>

