<?php
    include_once "../head/sql_header.php";

    $name = $_POST["username"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $q = $_POST["q"];
    $a = $_POST["a"];
    
    $query = "SELECT login FROM users WHERE login = '$name'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
    } else { 
        if (mysqli_num_rows(mysqli_query($conn, $query)) != 0) {
            $data = (object) array ("type" => "error", "er" => "name");
            echo json_encode($data);
        } else {
            $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (id, login, pass_hash, email, q, a, status,  pass_expire) VALUES (NULL, '$name', '$pass_hash', '$email', '$q', '$a', 0, DEFAULT)";
            
            if(!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
            } else{
                $query = "SELECT id FROM users WHERE login = '$name'";

                if(!mysqli_query($conn, $query)){
                    $data = (object) array ("type" => "error", "er" => "db");
                    echo json_encode($data);
                    die;
                }
                $assoc = mysqli_fetch_assoc(mysqli_query($conn, $query));
                $id = $assoc['id'];
                
                $query = "INSERT INTO tokens (id, token) VALUES ('$id', '-1')";
                if(!mysqli_query($conn, $query)){
                    $data = (object) array ("type" => "error", "er" => "db");
                    echo json_encode($data);
                    die;
                }
                $data = (object) array ("type" => "success");
                echo json_encode($data);
            }
        }
    }
?>