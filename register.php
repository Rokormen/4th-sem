<?php
/**
 * \file register.php
 * 
 * Файл, играющимй роль страницы регистрации.
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
    <title>Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script>
        function Send_n_recieve() {
                $("#ename").html("");
                $("#eemail").html("");
                $("#epass").html("");
                $("#ean").html("");

            if ($("#name").val() == "") {
                $("#ename").html("<div class='alert alert-warning' role='alert'>Name isn't set</div>");
                return "zeroname";
            } else if ($("#email").val() == "") {
                $("#eemail").html("<div class='alert alert-warning' role='alert'>Email isn't set</div>");
                return "zeroemail";
            } else if (($("#pass").val() != $("#apass").val()) || ($("#pass").val() == "") || ($("#apass").val() == "")) {
                $("#epass").html("<div class='alert alert-warning' role='alert'>Passwords are not equal or null</div>");
                return "zeropass";
            } else if ($("#question").val() == "" || $("#anwser").val() == "") {
                $("#ean").html("<div class='alert alert-warning' role='alert'>Question or anwser aren't set</div>");
                return "zeroq";
            }
            else {
                $.ajax(
                    {
                        url: "back/newuser.php",
                        method: "POST",
                        data: {
                            username: $("#name").val(),
                            pass: $("#pass").val(),
                            email: $("#email").val(),
                            q: $("#question").val(),
                            a: $("#anwser").val()
                        },
                        success: function (succ) {
                            succ = JSON.parse(succ);
                            switch (succ.type) {
                                case "error":
                                    switch (succ.er) {
                                        case "name":
                                            alert("Name already exists");
                                            return "name";
                                        break;
                                        case "db":
                                            alert("Cannot connect to the database. Try again later");
                                            return "db";
                                        break;
                                        case "email":
                                            alert("Email is not valid");
                                            return "email";
                                        break;
                                    }
                                break;
                                case "success":
                                    alert("Now you can login in");
                                    location.assign("index.php");
                                    return "done";
                                break;
                            }
                        }
                    }
                )
            }
        }
    </script>
</head>
<body>
<div class="grid-container">
  <div class="item1">Registration</div>
  <div class="item2">
      <a href="index.php">Main page</a>
      <a href="login.php">Enter the chat</a>
  </div>
  <div class="item3">
      <div class="space">To register you need to fill all the spaces</div>
      <form>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Nickname</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" placeholder="Your nickname">
            </div>
        </div>
        <div id="ename"></div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" placeholder="Email">
            </div>
        </div>
        <div id="eemail"></div>
        <div class="form-group row">
            <label for="pass" class="col-sm-2 col-form-label">Password</label>
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
            <label for="question" class="col-sm-5 col-form-label">Qusetion: Your favourite </label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="question" placeholder="pet?">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <input type="text" class="form-control" id="anwser" placeholder="Anwser">
            </div>
        </div>
        <div id="ean"></div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-warning" onclick="Send_n_recieve()">Register</button>
            </div>
        </div>
      </form>
  </div>  
  <div class="item4"></div>
</div>
    
</body>
</html>