<?php
    include_once "../head/sql_header.php";

    if($_GET['admin'] == 123){
        $name = $_GET['name'];
        switch($_GET['type']){
            case "ban":
                $query = "UPDATE users SET status='-1' WHERE login= '$name'";
                if (!mysqli_query($conn, $query)){
                    $data = (object) array ("type" => "error", "er" => "db");
                    echo json_encode($data);
                    die;
                } else {
                    $data = (object) array ("type" => "success");
                    echo json_encode($data);
                    die;
                }
            break;
            case "razban":
                $query = "UPDATE users SET status='0' WHERE login= '$name'";
                if (!mysqli_query($conn, $query)){
                    $data = (object) array ("type" => "error", "er" => "db");
                    echo json_encode($data);
                    die;
                } else {
                    $data = (object) array ("type" => "success");
                    echo json_encode($data);
                    die;
                }
            break;
        }
    }
?>