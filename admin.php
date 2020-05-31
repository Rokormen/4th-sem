<!DOCTYPE html>
<html>
<head>
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="style/style.css">
    <title>Admin page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script>

        function promote() {
            if($("#admin").val() != "") {
                $.ajax({
                method: "POST",
                url: "back/change.php",
                data: {
                    type: "admin",
                    name: $("#admin").val()
                },
                success: function (succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "error":
                            switch (succ.er) {
                                case "db":
                                    alert("Server is not responding. Try again later");
                                    break;
                                case "cant":
                                    alert("Cannot promote this user");
                                    break;
                            }
                        break;
                        case "success":
                            alert("He is an admin now");
                        break;
                    }
                }
            })}
        }

        function ban() {
            $.ajax({
                method: "POST",
                url: "back/change.php",
                data: {
                    type: "ban",
                    login: $("#banid").val()
                },
                success: function (succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "error":
                            switch (succ.er) {
                                case "db":
                                    alert("Server is not responding. Try again later");
                                    break;
                                case "cant":
                                    alert("Cannot ban this user");
                                    break;
                            }
                        break;
                        case "success":
                            alert("User is banned now");
                        break;
                    }
                }
            })
        }

        function razban() {
            $.ajax({
                method: "POST",
                url: "back/change.php",
                data: {
                    type: "unban",
                    login: $("#unban").val()
                },
                success: function (succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "error":
                            switch (succ.er) {
                                case "db":
                                    alert("Server is not responding. Try again later");
                                    break;
                                case "cant":
                                    alert("Cannot unban this user");
                                    break;
                            }
                        break;
                        case "success":
                            alert("User is unbanned now");
                        break;
                    }
                }
            })
        }
    </script>
</head>
<body>
<div class="grid-container">
  <div class="item1">Admin page</div>
  <div class="item2">
    <a href="welcome.php">Pick a chat</a>
    <a href="profile.php">Profile</a>
    <a href="back/logout.php">Logout</a>
  </div>
  <div class="item3">
      <div class="space">Your admin page</div>
    <form>
        <div class="form-group row">
            <label for="pass" class="col-sm-2 col-form-label">Who to ban</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="banid" placeholder="Login">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-warning" onclick="ban()">Ban</button>
            </div>
        </div>
    </form>
    <form>
        <div class="form-group row">
            <label for="pass" class="col-sm-2 col-form-label">Who to unban</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="unban" placeholder="Login">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-warning" onclick="razban()">Razban</button>
            </div>
        </div>
    </form>
    <form>
        <div class="form-group row">
            <label for="pass" class="col-sm-2 col-form-label">Who to make admin</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="admin" placeholder="Login">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-warning" onclick="promote()">Promote</button>
            </div>
        </div>
    </form>
    </div>
  <div class="item4"></div>
</div>
    
</body>
</html>