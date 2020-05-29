<?php
    include_once "../head/sql_header.php";
    $name = "";
    $pass = "";

    if (isset($_POST["name"])){
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    }

    if(strcasecmp($name,"")){
        login($conn, $name, $pass);
    }
//==========================================================================================
    function login($conn, $name, $pass){
    $query = "SELECT id, login, pass_hash FROM users WHERE login = '$name'";
    if (!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
        return "noconn";
        die;
    } else {
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, $query));

            if (strcasecmp($name, $assoc["login"])){
                $data = (object) array ("type" => "error", "er" => "name");
                echo json_encode($data);
                return("inname");
                die;
            }

            if (!password_verify($pass, $assoc["pass_hash"])){
                $data = (object) array ("type" => "error", "er" => "pass");
                echo json_encode($data);
                return ("inpass");
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
                return("noconn");
                die;
            } else{
                $data = (object) array ("type" => "success", "token" => $token);
                echo json_encode($data);
                return("alldone");
                die;
            }
        }
    }
?>