<?php
/**
 * \file msg.php
 * 
 * Файл-обработчик для отправки сообщения по токену комнаты. 
 *
 */
    include_once "../head/sql_header.php";

    $room_id = $_POST['room'];
    $token = $_POST['token'];
    $msg = $_POST['msg'];

    $query = "SELECT users.login FROM users INNER JOIN tokens ON tokens.id = users.id WHERE tokens.token='$token'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die;
    }

    $login = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $login = $login['login'];

    $query = "SELECT msg FROM rooms WHERE id= '$room_id'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die;
    }

    $msgm = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $msgm = json_decode($msgm['msg']);

    $msg = (object) array ("login" => $login, "msg" => $msg);
    
    array_push($msgm, $msg);

    $msgm = json_encode($msgm, JSON_UNESCAPED_UNICODE);
    
    $query = "UPDATE rooms SET msg='$msgm' WHERE id='$room_id'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die;
    } else {
        $data = (object) array ("type" => "success", "tab" => $msgm);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die;
    }
?>