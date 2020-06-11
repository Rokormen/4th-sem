<?php
/**
 * \file change.php
 * 
 * Файл-обработчик для страницы профиля и администрирования. Содержит в себе функции смены пароля, почты, бана, разбана и повышения до статуса админа
 *
 */
    include_once "../head/sql_header.php";

        /**
         * \brief Смена пароля
         * Функция смены пароля. Устанавливает новый пароль для аккаунта.
         * \param $id ID юзера
         * \param $conn Соединение с базой данных
         * \param $pass Новый пароль юзера
         * \return "alldone", если все прошло успешно, либо ошибку
         */
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
        
        /**
         * \brief Смена почты
         * Функция, которая проверяет почту на валидность и при соблюдении условий привязывает ее к аккаунту
         * \param $id ID юзера
         * \param $conn Соединение с базой данных
         * \param $email Новый адрес электронной почты
         * \return "alldone", если все прошло успешно, либо ошибку "noconn" (База данных), "notemail" (Почта не почта)
         */
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

        /**
         * \brief Повышение
         * Функция, которая повышает определенного пользователя до статуса администратора
         * \param $conn Соединение с базой данных
         * \param $login Имя пользователя, которого необходимо повысить
         * \return "alldone", если все прошло успешно, либо ошибку "noconn" (База данных), "cant" (Нельзя выполнить операцию)
         */
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

        /**
         * \brief Бан
         * Функция, которая банит пользователя. Администраторы могут банить пользователей. Суперадминистраторы могут также банить администраторов
         * \param $conn Соединение с базой данных
         * \param $userstat Статус запрашивающего пользователя
         * \param $login Имя пользователя, которого необходимо забанить
         * \return "alldone", если все прошло успешно, либо ошибку "noconn" (База данных), "cant" (Нельзя выполнить операцию)
         */
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

        /**
         * \brief Освобождение от бана
         * Функция, которая освобождает от бана выбранного пользователя
         * \param $conn Соединение с базой данных
         * \param $login Имя пользователя, которого необходимо освободить от бана
         * \return "alldone", если все прошло успешно, либо ошибку "noconn" (База данных), "cant" (Нельзя выполнить операцию)
         */
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

        /**
         * \brief Удаление комнаты
         * Удаляет комнату при указании названия комнаты
         * \param $conn Соединение с базой
         * \param $room Название комнаты
         * \return "alldone", если все прошло успешно, либо ошибку "noconn" (База данных), "noroom" (Не существует комнаты)
         */
        function deleteroom($conn, $room){ 
            $query = "SELECT name FROM rooms WHERE name='$room'";
            $result = mysqli_query($conn, $query);
            if (!$result){
                $data = (object) array ("type" => "error", "er" => "noconn");
                echo json_encode($data);
                return "noconn";
                die;
            }
            if (mysqli_num_rows($result) < 1){
                $data = (object) array ("type" => "error", "er" => "noroom");
                echo json_encode($data);
                return "noroom";
                die;
            }
            $query = "DELETE FROM rooms WHERE name='$room'";
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

    if(isset($_COOKIE['token']) || !strcasecmp($token, "unit")){
        
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
        

        //================================================================
        
        if (strcasecmp($token, "unit") || $userstat == 2){
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

        if (!strcasecmp($_POST['type'], "delete") && ($userstat == 1 || $userstat == 2)) {
            $room = $_POST['name'];
            deleteroom($conn, $room);
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
    }
?>