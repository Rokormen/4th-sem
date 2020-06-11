<?php
/*! \mainpage Chatroom
*
* \section intro_sec Введение
*
* Данный сайт предназначен для того, чтобы люди могли общаться посредством чат-комнат. Разработан как проект для университета.
*
* \section release Release Summary
* \subsection ver V 1.0.1
* Исправлны баги с оставшимися cookie токена пользователя и токена комнаты <br>
* Добавлен новый функционал администраторов: Удаление комнат
* \subsection ver V 1.0.0
* Сайт был запущен
* 
*/

/**
 * \file login.php
 * 
 * Файл играет роль страницы авторизации. На ней пользователь может ввести имя и пароль, чтобы зайти в чат под своими данными
 *
 */
?>
<!DOCTYPE html>
<html>
<head>
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="style/style.css">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script>
        function Sign_in() {
            $("#ename").html("");
            $("#epass").html("");

            if ($("#name").val() == "") {
                $("#ename").html("<div class='alert alert-warning' role='alert'>Name isn't set</div>");
            } else if ($("#pass").val() == "") {
                $("#epass").html("<div class='alert alert-warning' role='alert'>Enter password</div>");
            } else {
                $.ajax({
                    method: "POST",
                    url: "back/login_form.php",
                    data: {
                        name: $("#name").val(),
                        pass: $("#pass").val()
                    },
                    success: function (succ) {
                        succ = JSON.parse(succ);
                        switch (succ.type) {
                            case "success":
                                document.cookie = "token="+succ.token+";path=/; max-age=" + 60*60*24; 
                                location.assign("welcome.php");
                            break;
                            case "error":
                                switch (succ.er) {
                                    case "name":
                                        $("#ename").html("<div class='alert alert-warning' role='alert'>Name is incorrect</div>");
                                    break;
                                    case "pass":
                                        $("#epass").html("<div class='alert alert-warning' role='alert'>Password is incorrect</div>");
                                    break;
                                    case "db":
                                        alert("Server is going through some trouble. Try again later");
                                    break;
                                    case "ban":
                                        alert("Sorry, but you are banned from this server");
                                    break;
                                }
                            break;
                        }
                    }
                })
            }
        }
    </script>
</head>
<body>
<div class="grid-container">
  <div class="item1">Login form</div>
  <div class="item2">
    <a href="index.php">Main page</a>
    <a href="register.php">Register</a>
    <?php
        if(isset($_COOKIE['token'])){
            include_once "head/sql_header.php";
            $token = $_COOKIE['token'];
            $query = "SELECT users.login FROM users INNER JOIN tokens ON users.id = tokens.id WHERE tokens.token ='$token'";
            $result = mysqli_query($conn, $query);
            if (!$result){
                alert("Server is not responding");
            } else {
                $assoc = mysqli_fetch_assoc($result);
                $name = $assoc['login'];
                echo "<a href='welcome.php'>Continue to the site with $name</a>";
            }
    }
    ?>
  </div>
  <div class="item3">
    <div class="space">Type in your nickname and password</div>
    <form>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Nickname</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" placeholder="Your nickname">
            </div>
        </div>
        <div id="ename"></div>
        <div class="form-group row">
            <label for="pass" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="pass" placeholder="Password">
            </div>
        </div>
        <div id="epass"></div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-warning" onclick="Sign_in()">Sign in</button>
            </div>
        </div>
    </form>
    </div>  
  <div class="item4"></div>
</div>
    
</body>
</html>