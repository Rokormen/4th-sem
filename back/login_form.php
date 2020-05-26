<?php
    include_once "../head/sql_header.php";

    $name = $_POST["name"];
    $pass = $_POST["pass"];

    $query = "SELECT id, login, pass_hash FROM users WHERE login = '$name'";
    if (!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
    } else {
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, $query));

            if (strcasecmp($name, $assoc["login"])){
                $data = (object) array ("type" => "error", "er" => "name");
                echo json_encode($data);
                die;
            }

            if (!password_verify($pass, $assoc["pass_hash"])){
                $data = (object) array ("type" => "error", "er" => "pass");
                echo json_encode($data);
                die;
            }

            $id = $assoc["id"];
            if(isset($_COOKIE["token"])){
                setcookie("token", "", -3600);
            }

            $token = bin2hex(random_bytes(32));
            $query = "UPDATE tokens SET token= '$token' WHERE id = '$id'";
            
            if(!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
            } else{
                $data = (object) array ("type" => "success", "token" => $token);
                echo json_encode($data);
            }
        }
?>