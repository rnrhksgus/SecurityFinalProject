<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');
    $var = $_GET['m'];
    if ($var == "checkId"){
        $user_id = $_GET['user_id'];
        $user_id = stripslashes($user_id);
        $user_id = mysqli_real_escape_string($con, $user_id);
        $sql = "select count(*) as cnt from user where user_id = '$user_id'";
        $result = mysqli_query($con, $sql);
        if (mysqli_fetch_object($result)->cnt == 0) {
            echo "N";
        } else {
            echo "Y";
        }
        mysqli_free_result($result);
    } else if ($var == "checkCarIP"){
        $car_ip = $_GET['car_ip'];
        $car_ip = stripslashes($car_ip);
        $car_ip = mysqli_real_escape_string($con, $car_ip);
        $sql = "select count(*) as cnt from car where car_ip = '$car_ip'";
        $result = mysqli_query($con, $sql);
        if (mysqli_fetch_object($result)->cnt == 0) {
            $sql = "select count(*) as cnt from car_auth where car_ip = '$car_ip'";
            $result = mysqli_query($con, $sql);
            if (mysqli_fetch_object($result)->cnt == 1) {
                echo "N";
            } else {
                echo "Y";
            }
        } else {
            echo "Y";
        }
        mysqli_free_result($result);
    } else if ($var == "checkCarPin"){
        $car_ip = $_GET['car_ip'];
        $car_pin = $_GET['car_pin'];
        $car_ip = stripslashes($car_ip);
        $car_pin = stripslashes($car_pin);
        $car_ip = mysqli_real_escape_string($con, $car_ip);
        $car_pin = mysqli_real_escape_string($con, $car_pin);
        $sql = "select count(*) as cnt from car_auth where car_ip = '$car_ip' and car_pin = '$car_pin'";
        $result = mysqli_query($con, $sql);
        if (mysqli_fetch_object($result)->cnt == 0) {
            echo "Y";
        } else {
            echo "N";
        }
        mysqli_free_result($result);
    } else if($var == "board_list"){
        $page = $_GET['page'];
        $sql = "select count(*) as cnt from board";
        $result = mysqli_query($con, $sql);
        $total_cnt = mysqli_fetch_object($result)->cnt;
        $sql_page = ($page - 1) * 10;
        $sql = "select * from board order by board_num desc LIMIT $sql_page, 10;";
        $result = mysqli_query($con, $sql);
        $output = "
            <table>
                <thead>
                    <tr>
                        <th>번호</th>
                        <th width=50%>제목</th>
                        <th>작성자</th>
                        <th>작성일</th>
                    </tr>
                </thead>
                <tbody>
        ";
        while ($row = mysqli_fetch_object($result)){
            $date = strtotime($row->board_date);
            $date = date('Y-m-d', $date);
            $output .= "
                <tr>
                    <td>$row->board_num</td>
                    <td class='check_pw'><a href='board_view.php?board_num=$row->board_num' rel='external'>$row->board_title</a></td>
                    <td>$row->board_user_id</td>
                    <td>$date</td>
                </tr>
            ";
        }
        $output .= "
                </tbody>
            </table>
            <br>
            <div>
        ";
        for ($i = 1; $i <= (($total_cnt-1)/10) +1 ; $i++) {
            if($page == $i){
                $output .= "
                    <span id='page_span'> $i <span>
                ";
            } else {
                $output .= "
                    <a href='board.php?page=$i' class='a_bottom' rel='external'> $i </a>
                ";
            }
        }
        $output .= "
            </div>
            <br>
            ";
        echo "$output";
        mysqli_free_result($result);
    }
    else if($var == "board_view"){
       $board_num = $_GET['board_num'];
       $sql = "select * from board where board_num = $board_num";
       $result = mysqli_query($con, $sql);
       $row = mysqli_fetch_object($result);
       $output['board_title'] = $row->board_title;
       $output['board_user_id'] = $row->board_user_id;
       $output["board_content"] = $row->board_content;
       echo json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
       mysqli_free_result($result);
   }
   else if($var == "board_pw_check"){
     $board_pw = $_GET['board_pw'];
     $board_num = $_GET['board_num'];
     $board_pw = stripslashes($board_pw);
     $board_num = stripslashes($board_num);
     $board_pw = mysqli_real_escape_string($con, $board_pw);
     $board_num = mysqli_real_escape_string($con, $board_num);
     echo "$board_num // $board_pw";
   }
    mysqli_close($con);
?>
