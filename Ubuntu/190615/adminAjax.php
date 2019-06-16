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
       $sql = "select * from server ";
       $result = mysqli_query($con, $sql);
       $output = "
           <table id='server_table'>
               <thead>
                   <tr>
                       <th style='width: 10%'>IP</th>
                       <th style='width: 15%'>MAC</th>
                       <th style='width: 20%'>script_name</th>
                       <th>request_uri</th>
                       <th style='width: 15%'>date</th>
                   </tr>
               </thead>
               <tbody>
       ";
       while ($row = mysqli_fetch_object($result)){
           $date = strtotime($row->TIME);
           $date = date('Y-m-d H:i:s', $date);
           $output .= "
               <tr class='context-menu-one btn btn-neutral'>
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
                       <th>user_id</th>
                       <th>user_name</th>
                   </tr>
               </thead>
               <tbody>
       ";
       while ($row = mysqli_fetch_object($result)){
           $output .= "
               <tr class='context-menu-one btn btn-neutral'>
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
                       <th style='width: 15%'>아이디</th>
                       <th style='width: 20%'>이름</th>
                       <th style='width: 20%'>IP</th>
                   </tr>
               </thead>
               <tbody>
       ";
       while ($row = mysqli_fetch_object($result)){
           $output .= "
               <tr class='context-menu-one btn btn-neutral'>
                   <td>$row->user_id</td>
                   <td>$row->user_name</td>
                   <td>$row->session_ip_addr</td>
               </tr>
           ";
       }
       $output .= "
           </tbody>
       </table>
       ";
       echo "$output";
   } else if ($var == "admin_block_list"){
       $sql = "select * from block";
       $result = mysqli_query($con, $sql);
       $output = "
           <table id='block_table'>
               <thead>
                   <tr>
                       <th style='width: 40%'>IP</th>
                       <th>MAC</th>
                   </tr>
               </thead>
               <tbody>
       ";
       while ($row = mysqli_fetch_object($result)){
           $output .= "
               <tr class='context-menu-two btn btn-neutral'>
                   <td>$row->ip_addr</td>
                   <td>$row->mac_addr</td>
               </tr>
           ";
       }
       $output .= "
           </tbody>
       </table>
       ";
       echo "$output";
   } else if ($var == "admin_last_login"){
       $output = "last login : ";
       $output .= "2019-06-12 / 14:06:31";
       echo "$output";
   } else if ($var == "admin_menu_block"){
       $ip_addr = $_GET['ip_addr'];
       $ip_addr = stripslashes($ip_addr);
       $ip_addr = mysqli_real_escape_string($con, $ip_addr);
       $mac_addr = $_GET['mac_addr'];
       $mac_addr = stripslashes($mac_addr);
       $mac_addr = mysqli_real_escape_string($con, $mac_addr);

       if($ip_addr != '' && $mac_addr != ''){
            $sql = "insert into block (ip_addr, mac_addr) values ('$ip_addr', '$mac_addr')";
            exec("sudo iptables -A INPUT -s ".$ip_addr." -j DROP");
            exec("sudo iptables -A INPUT -m mac --mac-source ".$mac_addr." -j DROP");
            $result = mysqli_query($con, $sql);
            echo "차단 완료";
       } else if ($ip_addr != ''){
           $sql = "insert into block (mac_addr) values ('$mac_addr')";
           exec("sudo iptables -A INPUT -m mac --mac-source ".$mac_addr." -j DROP");
           $result = mysqli_query($con, $sql);
       } else if ($ip_addr != ''){
           $sql = "insert into block (ip_addr) values ('$ip_addr')";
           exec("sudo iptables -A INPUT -s ".$ip_addr." -j DROP");
           $result = mysqli_query($con, $sql);
       } else {
           echo "오류";
       }

   } else if ($var == "admin_menu_unblock"){
       $ip_addr = $_GET['ip_addr'];
       $ip_addr = stripslashes($ip_addr);
       $ip_addr = mysqli_real_escape_string($con, $ip_addr);
       $mac_addr = $_GET['mac_addr'];
       $mac_addr = stripslashes($mac_addr);
       $mac_addr = mysqli_real_escape_string($con, $mac_addr);
       if($ip_addr != ''){
           $sql = "delete from block where ip_addr = '$ip_addr'";
           exec("sudo iptables -D INPUT -s ".$ip_addr." -j DROP");
           $result = mysqli_query($con, $sql);
       }
       if($mac_addr != ''){
           $sql = "delete from block where mac_addr = '$mac_addr'";
           exec("sudo iptables -D INPUT -m mac --mac-source ".$mac_addr." -j DROP");
           $result = mysqli_query($con, $sql);
       }
       echo "차단 해제";
   } else if ($var == "admin_check"){
       $user_id = $_GET['user_id'];
       $user_id = stripslashes($user_id);
       $user_id = mysqli_real_escape_string($con, $user_id);

       $sql = "select user_type from user where user_id='$user_id'";
       $result = mysqli_query($con, $sql);
       if (mysqli_fetch_object($result)->user_type == 0) {
           echo "N";
       } else {
           echo "Y";
       }
   }
   mysqli_free_result($result);
?>

