<?php
    include_once "../head/sql_header.php";

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
        
        //==================================================================
        function setpass($id, $conn){
        
            $pass = $_POST['pass'];
            
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
        
        
        function setemail($id, $conn){
        
            $email = $_POST['email'];
            
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

        function promote($conn){
            $status = 1;
            $login = $_POST['name'];
            
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

        function ban($conn, $userstat){
            $status = -1;
            $login = $_POST['login'];

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

        function unban($conn){
            $status = 0;
            $login = $_POST['login'];

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
            setpass($id, $conn);
        }

        if (!strcasecmp($_POST['type'], "email")){
            setemail($id, $conn);
        }

        if (!strcasecmp($_POST['type'], "admin") && ($userstat == 1 || $userstat == 2)) {
            promote($conn);
        }

        if (!strcasecmp($_POST['type'], "ban") && ($userstat == 1 || $userstat == 2)) {
            ban($conn, $userstat);
        }

        if (!strcasecmp($_POST['type'], "unban") && ($userstat == 1 || $userstat == 2)) {
            unban($conn);
        }

    }
?>