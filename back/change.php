<?php
    include_once "../head/sql_header.php";

    if(isset($_COOKIE['token'])){
        $token = $_COOKIE['token'];

        $query = "SELECT users.id FROM users INNER JOIN tokens ON users.id = tokens.id WHERE token='$token'";
            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                die;
        }

        $id = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $id = $id['id'];

        if(!strcasecmp($_POST['type'], "password")){
            $pass = $_POST['pass'];
            
            $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
            
            $query = "UPDATE users SET pass_hash='$pass_hash' WHERE id='$id'";
            
            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                die;
            } else {
                $data = (object) array ("type" => "success");
                echo json_encode($data);
                die;
            }
        }  
        
        if (!strcasecmp($_POST['type'], "email")){
            $email = $_POST['email'];
            
            $query = "UPDATE users SET email='$email' WHERE id='$id'";
            
            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                die;
            } else {
                $data = (object) array ("type" => "success");
                echo json_encode($data);
                die;
            }
        } 
        
        if (!strcasecmp($_POST['type'], "admin")) {
            $status = 1;
            $name = $_POST['name'];
            
            $query = "UPDATE users SET status='$status' WHERE login='$name'";
            
            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                die;
            } else {
                $data = (object) array ("type" => "success");
                echo json_encode($data);
                die;
            }
        }

        if (!strcasecmp($_POST['type'], "ban")) {
            $status = -1;
            $login = $_POST['login'];

            $query = "UPDATE users SET status='$status' WHERE login='$login'";

            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                die;
            } else {
                $data = (object) array ("type" => "success");
                echo json_encode($data);
                die;
            }
        }

        if (!strcasecmp($_POST['type'], "unban")) {
            $status = 0;
            $login = $_POST['login'];

            $query = "UPDATE users SET status='$status' WHERE login='$login'";

            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                die;
            } else {
                $data = (object) array ("type" => "success");
                echo json_encode($data);
                die;
            }
        }
    }
?>