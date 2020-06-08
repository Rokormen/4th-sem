<?php
/**
 * \file profile.php
 * 
 * Файл, играющий роль профиля игрока. Здесь он может поменять пароль, почту или перейти на страницу администрирования.
 *
 */
    include_once "head/sql_header.php";

    if(isset($_COOKIE["token"])){
        $token = $_COOKIE["token"];
        $query = "SELECT users.login, users.email, users.status FROM users INNER JOIN tokens ON tokens.id = users.id WHERE tokens.token= '$token'";
        if (!mysqli_query($conn, $query)){
            alert("Server is not responding");
        } else {
            $assoc = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $login = $assoc['login'];
            $email = $assoc['email'];
            $status = $assoc['status'];
        }
    }
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
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script>

        function adminhtml() {
            if (<?php echo $status ?> == 1 || <?php echo $status ?> == 2) {
                $("#admin").html("<a href='admin.php' class='btn btn-warning btn-sm'>Admin page</a>");
            }
        }

        function changePass() {
            $("#epass").html("");
            if(($("#pass").val() != $("#apass").val()) || ($("#pass").val() == "")){
                $("#epass").html("<div class='alert alert-warning' role='alert'>Passwords are not equal</div>");
            } else {
            $.ajax({
                method: "POST",
                url: "back/change.php",
                data: {
                    type: "password",
                    pass: $("#pass").val(),
                },
                success: function (succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "success":
                            $("#epass").html("<div class='alert alert-warning' role='alert'>New password is set</div>");
                        break;
                        case "error":
                            alert("Server is not responding. Try again later");
                        break;
                    }
                }
            })}
        }

        function changeEmail() {
            $("#eemail").html("");
            if($("#email").val() == ""){
                $("#eemail").html("<div class='alert alert-warning' role='alert'>Email is not set</div>");
            } else {
            $.ajax({
                method: "POST",
                url: "back/change.php",
                data: {
                    type: "email",
                    email: $("#email").val(),
                },
                success: function (succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "success":
                            $("#eemail").html("<div class='alert alert-warning' role='alert'>New email is set</div>");
                        break;
                        case "error":
                            switch(succ.er){
                                case "db":
                                    alert("Server is not responding. Try again later");
                                break;
                                case "email":
                                    alert("Not a valid email");
                                break;
                            }
                        break;
                    }
                }
            })}
        }
    </script>
</head>
<body onload="adminhtml()">
<div class="grid-container">
  <div class="item1">Your profile</div>
  <div class="item2">
      <a href="welcome.php">Pick a chat</a>
      <a href="back/logout.php">Logout</a>
  </div>
  <div class="item3">
        <?php echo $login?>'s profile. <br>
        <div class="space">Want to change your password?</div> 
        <form>
        <div class="form-group row">
            <label for="pass" class="col-sm-2 col-form-label">New pass</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="pass" placeholder="Password">
            </div>
        </div>
        <div class="form-group row">
            <label for="apass" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="apass" placeholder="For verification">
            </div>
        </div>
        <div id="epass"></div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-warning" onclick="changePass()">Change</button>
            </div>
        </div>
        </form>
        <div class="space">Want to change your email?</div>
        <form>
        <div class="form-group row">
            <label for="apass" class="col-sm-2 col-form-label">New email</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="email" placeholder="<?php echo $email?>">
            </div>
        </div>
        <div id="eemail"></div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-warning" onclick="changeEmail()">Change</button>
            </div>
        </div>
        </form>
    </div>
  <div class="item4">
      <div id="admin">

      </div>
  </div>
</div>
    
</body>
</html>