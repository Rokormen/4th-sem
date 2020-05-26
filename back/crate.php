<?php
    include_once "../head/sql_header.php";

    $name = $_POST['name'];

    $room_id = bin2hex(random_bytes(32));

    $query = "INSERT INTO rooms (id, name, msg, user, status) VALUES ('$room_id', '$name', '[]', '[]', 0)";
    if (!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
        die;
    } else {
        $data = (object) array ("type" => "success", "room_id" => $room_id);
        echo json_encode($data);
        die;
    }

?>