<?php
/**
 * \file test.php
 * 
 * Файл для проведения юнит тестов различных PHP функций
 *
 */
use PHPUnit\Framework\TestCase;
require "../head/sql_header.php";
require_once "../back/newuser.php"; 
require_once "../back/login_form.php";
/**
 * \brief Класс для юнит-тестирования
 * Класс, тестирующий различные функции перед выгрузкой данных на сервер
 */
class TestSystem extends TestCase
    {
        /**
         * \brief Тест регистрации
         * Юнит тест для функции регистрации. Тестирует функцию на создание нового пользователя, а также ошибки возникающие во время создания
         */
        public function testRegistration()
        {   
            require "../head/sql_header.php";
            //Clear db
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'newuser'"));
            $id = $assoc['id'];
            mysqli_query($conn, "DELETE FROM tokens WHERE id='$id'");
            mysqli_query($conn, "DELETE FROM users WHERE login='newuser'");
            //Proper work
            $this->assertSame("alldone", register($conn, "newuser", "newuser@site.my", "password", "question", "anwser"));
            //Same name
            $this->assertSame("nameex", register($conn, "newuser", "newuser@site.my", "password", "question", "anwser"));
            //Email is not valid
            $this->assertSame("notemail", register($conn, "newnewuser", "newusersite", "password", "question", "anwser"));
            //Clear db
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'newuser'"));
            $id = $assoc['id'];
            mysqli_query($conn, "DELETE FROM tokens WHERE id='$id'");
            mysqli_query($conn, "DELETE FROM users WHERE login='newuser'");
        }

        /**
         * \brief Тест авторизации
         * Тестирование авторизации на правильную работу, а также ошибки авторизации
         */
        public function testLogin()
        {
            require "../head/sql_header.php";
            register($conn, "newuser", "newuser@site.my", "password", "question", "anwser");
            //Invalid name
            $this->assertSame("inname", login($conn, "notavalidname", "password"));
            //Invalid password
            $this->assertSame("inpass", login($conn, "newuser", "notavalidpassword"));
            //Proper work
            $this->assertSame("alldone", login($conn, "newuser", "password"));
            //Clear db
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'newuser'"));
            $id = $assoc['id'];
            mysqli_query($conn, "DELETE FROM tokens WHERE id='$id'");
            mysqli_query($conn, "DELETE FROM users WHERE login='newuser'");
        }
        
        /**
         * \brief Тест смены пароля и email
         * Тест функций смены пароля и email на странице профиля пользователя
         */
        public function testChangePHP()
        {
            require "../head/sql_header.php";
            register($conn, "testchange", "testchange@site.my", "password", "question", "anwser");
            $token = "unit";
            $userstat = 0;
            require_once "../back/change.php";
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'testchange'"));
            $id = $assoc['id'];
            //Change pass 
            $this->assertSame("alldone", setpass($id, $conn, "newpassword"));
            //Change email (not valid)
            $this->assertSame("notemail", setemail($id, $conn, "email"));
            //Change email
            $this->assertSame("alldone", setemail($id, $conn, "newtestchange@site.my"));
            //Clear db
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'testchange'"));
            $id = $assoc['id'];
            mysqli_query($conn, "DELETE FROM tokens WHERE id='$id'");
            mysqli_query($conn, "DELETE FROM users WHERE login='testchange'");
        }

        /**
         * \brief Тест администрирования
         * Тест функций администрирования: Бан юзера, Разбан юзера, Повышения юзера в админы, Удаление комнаты
         */
        public function testChangePHPAdmin()
        {
            require "../head/sql_header.php";
            register($conn, "testadmin", "testadmin@site.my", "password", "question", "anwser");
            register($conn, "testvictum", "testvictum@site.my", "password", "question", "anwser");
            $token = "unit";
            require_once "../back/change.php";
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'testadmin'"));
            mysqli_query($conn, "INSERT INTO rooms (id, name, msg, user, status) VALUES (1, 'testroom', '[]', '[]', 0)");
            $id = $assoc['id'];
            $userstat = 1;
            //Ban a person
            $this->assertSame("alldone", ban($conn, $userstat, "testvictum"));
            //Ban a banned user
            $this->assertSame("cant", ban($conn, $userstat, "testvictum"));
            //Unban a user
            $this->assertSame("alldone", unban($conn, "testvictum"));
            //Unban a not banned user
            $this->assertSame("cant", unban($conn, "testvictum"));
            //Promote to admin
            $this->assertSame("alldone", promote($conn, "testvictum"));
            //Ban an admin
            $this->assertSame("cant", ban($conn, $userstat, "testvictum"));
            //Ban an admin from superadmin state
            $userstat = 2;
            $this->assertSame("alldone", ban($conn, $userstat, "testvictum"));
            //Delete a room (not a room)
            $this->assertSame("noroom", deleteroom($conn, "testroom1"));
            //Delete a room 
            $this->assertSame("alldone", deleteroom($conn, "testroom"));
            //Clear db
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'testadmin'"));
            $id = $assoc['id'];
            mysqli_query($conn, "DELETE FROM tokens WHERE id='$id'");
            mysqli_query($conn, "DELETE FROM users WHERE login='testadmin'");
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE login = 'testvictum'"));
            $id = $assoc['id'];
            mysqli_query($conn, "DELETE FROM tokens WHERE id='$id'");
            mysqli_query($conn, "DELETE FROM users WHERE login='testvictum'");
            mysqli_query($conn, "DELETE FROM rooms WHERE name='testroom'");
        }
    }
?>