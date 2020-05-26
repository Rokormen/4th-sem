<?php
    include_once "../head/sql_header.php";

    $token = $_COOKIE['token'];

    $room_id = $_COOKIE['room_id'];
    setcookie("room_id", "", -3600);

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

    for($i = 0; $i < count($user); $i++){
        if (!strcasecmp($user[$i],$login)){
            array_splice($user, $i, 1);
        }
    }

    $user = json_encode($user);

    $query = "UPDATE rooms SET user='$user' WHERE id='$room_id'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
        die;
    }

    $str = $_GET['str'];

    switch ($str) {
        case "wel":
            header("Location: ../welcome.php");
            die;
        break;
        case "pro":
            header("Location: ../profile.php");
            die;
        break;
        case "log":
            header("Location: logout.php");
            die;
        break;
    }

?>
