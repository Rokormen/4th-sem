<?php
/**
 * \file get_table.php
 * 
 * Файл-обработчик для того, чтобы достать список комнат и отправить ее обратно в вызывающий файл (welcome.php)
 *
 */
    include_once "../head/sql_header.php";

    $query = "SELECT id, name FROM rooms WHERE status = '0'";

    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
    } else {
        $id = 0;
        $table = "<table id='tablein'><tr><th class='ff'>Name</th><th class='ss'>Connect</th></tr>";
        $tablet = mysqli_query($conn, $query);
        while ($assoc = mysqli_fetch_assoc($tablet)) {
            $id += 1;
            $table = $table . "<tr><td class='f' >".$assoc["name"]."</td><td class='s'><button type='button' onclick='connect(\"".$assoc["id"]."\")' class='btn btn-warning btn-sm' >Connect</button></tr></tr>";
        }
        $data = (object) array ("type" => "success", "table" => $table . "</table>");
        echo json_encode($data);
    }
?>