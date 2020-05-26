<?php
    include_once "../head/sql_header.php";

    $token = $_COOKIE['token'];
    $room_id = $_COOKIE['room_id'];

    $query = "SELECT users.login FROM users INNER JOIN tokens ON tokens.id = users.id WHERE tokens.token='$token'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
        die;
    }

    $login = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $login = $login['login'];

    $query = "SELECT user FROM rooms WHERE id= '$room_id'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
        die;
    } 

    $user = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $user = json_decode($user['user']);
    $flag = 0;

    if ($user == null){
        $user = array ($login);
    } else {
        foreach($user as $val){
            if (!strcasecmp($login, $val)){
                $flag = 1;
            }
        }
        if($flag != 1){
        array_push($user, $login);
        }
    }
    
    $user = json_encode($user);

    $query = "UPDATE rooms SET user='$user' WHERE id='$room_id'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
        die;
    }

    header("Location: ../chatroom.php");
    die;
?>