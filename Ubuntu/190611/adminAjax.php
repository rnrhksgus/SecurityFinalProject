<?php
    $con = mysqli_connect('localhost', 'user', '123456', 'user_db');
    $var = $_GET['m'];
    if ($var == "admin_board_list"){
        $sql = "select * from board where board_check=0 order by board_num asc";
        $result = mysqli_query($con, $sql);
        $output = "
            <table id='board_table'>
                <thead>
                    <tr>
                        <th style='width: 10%'>번호</th>
                        <th>제목</th>
                        <th style='width: 20%'>작성자</th>
                        <th style='width: 20%'>작성일</th>
                        <th style='width: 10%'>보기</th>
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
                    <td>$row->board_title</td>
                    <td>$row->board_user_id</td>
                    <td>$date</td>
                    <td><button type='button' class='btn_board_view'>보기</button></td>
                </tr>
            ";
        }
        $output .= "
            </tbody>
        </table>
        ";
        echo "$output";
    } else if ($var == "admin_board_view"){
        $board_num = $_GET['board_num'];
        $sql = "select * from board where board_num = $board_num";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_object($result);
        $date = strtotime($row->board_date);
        $date = date('Y-m-d', $date);
        $output['board_num'] = $row->board_num;
        $output['board_title'] = $row->board_title;
        $output['board_date'] = $date;
        $output['board_user_id'] = $row->board_user_id;
        $output["board_content"] = $row->board_content;
        echo json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        mysqli_free_result($result);
    } else if ($var == "admin_board_update"){
       $board_num = $_GET['board_num'];
       $sql = "update board set board_check=1 where board_num = $board_num";
       $result = mysqli_query($con, $sql);
       mysqli_free_result($result);
   } else if ($var == "admin_server_list"){
       $sql = "select * from server_log";
       $result = mysqli_query($con, $sql);
       $output = "
           <table id='server_table'>
               <thead>
                   <tr>
                       <th style='width: 15%'>IP</th>
                       <th style='width: 20%'>MAC</th>
                       <th style='width: 20%'>script_name</th>
                       <th>request_uri</th>
                       <th style='width: 20%'>date</th>
                   </tr>
               </thead>
               <tbody>
       ";
       while ($row = mysqli_fetch_object($result)){
           $date = strtotime($row->TIME);
           $date = date('Y-m-d', $date);
           $output .= "
               <tr>
                   <td>$row->REMOTE_ADDR</td>
                   <td>$row->MAC_ADDR</td>
                   <td>$row->SCRIPT_NAME</td>
                   <td>$row->REQUEST_URI</td>
                   <td>$date</td>
               </tr>
           ";
       }
       $output .= "
           </tbody>
       </table>
       ";
       echo "$output";
   } else if ($var == "admin_user_list"){
       $sql = "select * from user";
       $result = mysqli_query($con, $sql);
       $output = "
           <table id='user_table'>
               <thead>
                   <tr>
                       <th style='width: 15%'>user_id</th>
                       <th style='width: 20%'>user_name</th>
                   </tr>
               </thead>
               <tbody>
       ";
       while ($row = mysqli_fetch_object($result)){
           $output .= "
               <tr>
                   <td>$row->user_id</td>
                   <td>$row->user_name</td>
               </tr>
           ";
       }
       $output .= "
           </tbody>
       </table>
       ";
       echo "$output";
   } else if ($var == "admin_session_list"){
       $sql = "select * from user inner join session on user_id = session_user_id";
       $result = mysqli_query($con, $sql);
       $output = "
           <table id='session_table'>
               <thead>
                   <tr>
                       <th style='width: 15%'>user_id</th>
                       <th style='width: 20%'>user_name</th>
                   </tr>
               </thead>
               <tbody>
       ";
       while ($row = mysqli_fetch_object($result)){
           $output .= "
               <tr>
                   <td>$row->user_id</td>
                   <td>$row->user_name</td>
               </tr>
           ";
       }
       $output .= "
           </tbody>
       </table>
       ";
       echo "$output";
   }
    mysqli_free_result($result);
?>

