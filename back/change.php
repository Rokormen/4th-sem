<?php
    include_once "../head/sql_header.php";

    if(isset($_COOKIE['token']) || $token == "unit"){
        
        if(isset($_COOKIE['token'])){
        $token = $_COOKIE['token'];
        

        $query = "SELECT users.id, users.status FROM users INNER JOIN tokens ON users.id = tokens.id WHERE token='$token'";
            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                die;
        }

        $id = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $userstat = $id['status'];
        $id = $id['id'];
        }

        //==================================================================
        function setpass($id, $conn, $pass){
            
            $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
            
            $query = "UPDATE users SET pass_hash='$pass_hash' WHERE id='$id'";
            
            if (!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                return "noconn";
                die;
            } else {
                $data = (object) array ("type" => "success");
                echo json_encode($data);
                return "alldone";
                die;
            }
        }  
        
        
        function setemail($id, $conn, $email){
        
            
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
                $data = (object) array ("type" => "error", "er" => "email");
                echo json_encode($data);
                return "notemail";
            } else {
            
                    $query = "UPDATE users SET email='$email' WHERE id='$id'";
            
                    if (!mysqli_query($conn, $query)){
                        $data = (object) array ("type" => "error", "er" => "db");
                        echo json_encode($data);
                        return "noconn";
                        die;
                    } else {
                        $data = (object) array ("type" => "success");
                        echo json_encode($data);
                        return "alldone";
                        die;
                    }
            }
        } 

        function promote($conn, $login){
            $status = 1;

            
            $query = "SELECT status FROM users WHERE login='$login'";
            $result = mysqli_query($conn, $query);
            if (!$result){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                return "noconn";
                die;
            } else { 
                $assoc = mysqli_fetch_assoc($result);
                if ($assoc['status'] == 0) {
                    $query = "UPDATE users SET status='$status' WHERE login='$login'";
            
                    if (!mysqli_query($conn, $query)){
                        $data = (object) array ("type" => "error", "er" => "db");
                        echo json_encode($data);
                        return "noconn";
                        die;
                    } else {
                        $data = (object) array ("type" => "success");
                        echo json_encode($data);
                        return "alldone";
                        die;
                    }
                } else {
                    $data = (object) array ("type" => "error", "er" => "cant");
                    echo json_encode($data);
                    return "cant";
                    die;
                }
            }
        }

        function ban($conn, $userstat, $login){
            $status = -1;


            $query = "SELECT status FROM users WHERE login='$login'";
            $result = mysqli_query($conn, $query);
            if (!$result){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                return "noconn";
                die;
            } else { 
                $assoc = mysqli_fetch_assoc($result);
                if ($assoc['status'] == 0 || ($assoc['status'] == 1 && $userstat == 2)) {
                    $query = "UPDATE users SET status='$status' WHERE login='$login'";

                    if (!mysqli_query($conn, $query)){
                        $data = (object) array ("type" => "error", "er" => "db");
                        echo json_encode($data);
                        return "noconn";
                        die;
                    } else {
                        $data = (object) array ("type" => "success");
                        echo json_encode($data);
                        return "alldone";
                        die;
                    }
                } else {
                    $data = (object) array ("type" => "error", "er" => "cant");
                        echo json_encode($data);
                        return "cant";
                        die;
                }
            }
        }

        function unban($conn, $login){
            $status = 0;


            $query = "SELECT status FROM users WHERE login='$login'";
            $result = mysqli_query($conn, $query);
            if (!$result){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                return "noconn";
                die;
            } else { 
                $assoc = mysqli_fetch_assoc($result);
                if ($assoc['status'] == -1) {
                    $query = "UPDATE users SET status='$status' WHERE login='$login'";

                    if (!mysqli_query($conn, $query)){
                        $data = (object) array ("type" => "error", "er" => "db");
                        echo json_encode($data);
                        return "noconn";
                        die;
                    } else {
                        $data = (object) array ("type" => "success");
                        echo json_encode($data);
                        return "alldone";
                        die;
                    }
                } else {
                    $data = (object) array ("type" => "error", "er" => "cant");
                        echo json_encode($data);
                        return "cant";
                        die;
                }
            }

            
        }

        //================================================================

        if(!strcasecmp($_POST['type'], "password")){
            $pass = $_POST['pass'];
            setpass($id, $conn, $pass);
            die;
        }

        if (!strcasecmp($_POST['type'], "email")){
            $email = $_POST['email'];
            setemail($id, $conn, $email);
            die;
        }

        if (!strcasecmp($_POST['type'], "admin") && ($userstat == 1 || $userstat == 2)) {
            $login = $_POST['name'];
            promote($conn, $login);
            die;
        }

        if (!strcasecmp($_POST['type'], "ban") && ($userstat == 1 || $userstat == 2)) {
            $login = $_POST['login'];
            ban($conn, $userstat, $login);
            die;
        }

        if (!strcasecmp($_POST['type'], "unban") && ($userstat == 1 || $userstat == 2)) {
            $login = $_POST['login'];
            unban($conn, $login);
            die;
        }

    }
?>