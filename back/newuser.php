<?php
/**
 * \file newuser.php
 * 
 * Файл-обработчик для страницы регистрации. Содержит функцию регистрации нового пользователя.
 *
 */
    include_once "../head/sql_header.php";
    $name = "";
    $email = "";
    $pass = "";
    $q = "";
    $a = "";

    if (isset($_POST["username"])){
    $name = $_POST["username"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $q = $_POST["q"];
    $a = $_POST["a"];
    }

    if(strcasecmp($name,"")){
        register($conn, $name, $email, $pass, $q, $a);
    }
    //==============================================================================================
    /**
    * \brief Регистрация
    * Функция регистрации нового пользователя на сайт.
    * \param $conn Соединение с базой данных
    * \param $name Имя пользователя
    * \param $email Почта пользователя
    * \param $pass Пароль пользователя
    * \param $q Секретный вопрос
    * \param $a Секретный ответ
    * \return "alldone", если все прошло успешно, либо ошибку "noconn" (База данных), "nameex" (Имя занято), "notemail" (Почта не почта)
    */
    function register($conn, $name, $email, $pass, $q, $a){

    $query = "SELECT login FROM users WHERE login = '$name'";
    if(!mysqli_query($conn, $query)){
        $data = (object) array ("type" => "error", "er" => "db");
        echo json_encode($data);
        return "noconn";
    } else { 
        if (mysqli_num_rows(mysqli_query($conn, $query)) != 0) {
            $data = (object) array ("type" => "error", "er" => "name");
            echo json_encode($data);
            return "nameex";
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
                $data = (object) array ("type" => "error", "er" => "email");
                echo json_encode($data);
                return "notemail";
            } else {
            $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (id, login, pass_hash, email, q, a, status,  pass_expire) VALUES (NULL, '$name', '$pass_hash', '$email', '$q', '$a', 0, DEFAULT)";
            
            if(!mysqli_query($conn, $query)){
                $data = (object) array ("type" => "error", "er" => "db");
                echo json_encode($data);
                return "noconn";
            } else {
                $query = "SELECT id FROM users WHERE login = '$name'";

                if(!mysqli_query($conn, $query)){
                    $data = (object) array ("type" => "error", "er" => "db");
                    echo json_encode($data);
                    return "noconn";
                    die;
                }
                $assoc = mysqli_fetch_assoc(mysqli_query($conn, $query));
                $id = $assoc['id'];
                
                $query = "INSERT INTO tokens (id, token) VALUES ('$id', '-1')";
                if(!mysqli_query($conn, $query)){
                    $data = (object) array ("type" => "error", "er" => "db");
                    echo json_encode($data);
                    return "noconn";
                    die;
                }
                $data = (object) array ("type" => "success");
                echo json_encode($data);
                return "alldone";
                }
            }
        }
    }
}
?>