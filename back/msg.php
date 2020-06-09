<?php
/**
 * \file msg.php
 * 
 * Файл-обработчик для отправки сообщения по токену комнаты. 
 *
 */
    include_once "../head/sql_header.php";

    /**
     * \brief Функция отправки сообщения
     * Функция берет ID комнаты, токен пользователя и сообщение. Сообщение и логин отправителя прикрепляются к текущему логу сообщений и затем отправляются в базу данных
     * \param $room_id ID комнаты
     * \param $token Токен отправителя
     * \param $msg Сообщение пользователя
     * \param $conn Соединение с базой данных
     * \return Так как это функция обработчик AJAX запросов, то она возвращает ответы с помощью echo. Формат: {type: success/error, tab/er: ....}
     */
    function msg($room_id, $token, $msg, $conn){
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
    }

    if(isset($_POST['msg'])){ 
        $room_id = $_POST['room'];
        $token = $_POST['token'];
        $msg = $_POST['msg'];
        msg($room_id, $token, $msg, $conn);
    }
?>
