<?php
/**
 * \file get_msg.php
 * 
 * Файл-обработчик для того, чтобы достать таблицу с сообщениями и отослать ее обратно в вызывающий файл chatroom.php
 *
 */
    include_once "../head/sql_header.php";

    $room_id = $_POST['room'];

    $query = "SELECT user, msg FROM rooms WHERE id= '$room_id'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die;
    } 

    $ser = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $user = $ser['user'];
    $msg = $ser['msg'];

    $data = (object) array ("type" => "success", "user" => $user, "msg" => $msg);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>