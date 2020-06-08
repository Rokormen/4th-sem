<?php
/**
 * \file login_form.php
 * 
 * Файл-обработчик для страницы авторизации login.php. Содержит функцию авторизации и отправляет обратно токен пользователя.
 *
 */
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
    /**
    * \brief Авторизация
    * Функция авторизации на сайт.
    * \param $conn Соединение с базой данных
    * \param $name Имя пользователя
    * \param $pass Пароль пользователя
    * \return "alldone", если все прошло успешно, либо ошибку "noconn" (База данных), "inname" (Неправильное имя), "inpass" (Неправильный пароль), "ban" (Пользователь в бане)
    */
    function login($conn, $name, $pass){
    $query = "SELECT id, login, pass_hash, status FROM users WHERE login = '$name'";
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

            if ($assoc['status'] == -1){
                $data = (object) array ("type" => "error", "er" => "ban");
                echo json_encode($data);
                return ("ban");
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