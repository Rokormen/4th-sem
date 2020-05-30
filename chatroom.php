<?php
    include_once "head/sql_header.php";

    if(isset($_COOKIE['room_id'])){
        $room_id = $_COOKIE['room_id'];
        $query = "SELECT name FROM rooms WHERE id = '$room_id'";
        if (!mysqli_query($conn, $query)){
            alert("Server is not responding");
        } else {
            if (mysqli_num_rows(mysqli_query($conn, $query)) < 1){
                header("Location: welcome.php");
            }
            $table = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $name = $table['name'];
            $token = $_COOKIE['token'];
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
    <title>Chat room</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script>
        let chat, user;

        function sendMes () {
            if ($("#msg").val() != ""){
            $.ajax({
                method: "POST",
                url: "back/msg.php",
                data: {
                    room: <?php echo "\"$room_id\"" ?>,
                    msg: $("#msg").val(),
                    token: <?php echo "\"$token\"" ?>
                },
                success: function (succ){
                    succ = JSON.parse(succ);
                    if (succ.type == "error" && succ.er == "db"){
                        alert("Server is not responding");
                    }
                }
            })
            }
            $("#msg").val("");
        }

        function parse(msg, ser) {
            chat = "<table>";
            user = "<table>";
            for (i = msg.length - 1; i >= 0; i--)
            {
                chat = chat + "<tr><td style='width:100px'><b>"+msg[i].login+"</b></td><td style='width:20px'> - </td><td style='width:100% padding-left:120px'>"+msg[i].msg+"</td></tr>";
            }
            chat = chat + "</table>";

            ser.forEach(b => {
                user = user + "<tr><td style='width:200px text-align:start'>" + b +"</td></tr>"
            })
            user = user + "</table>";

            $("#chat").html(chat);
            $("#user").html(user);
        }

        function getMes() {
            $.ajax({
                method: "POST",
                url: "back/get_msg.php",
                data: {
                    room: <?php echo "\"$room_id\"" ?>
                },
                success: function (succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "error":
                            if(succ.er == "db"){
                                alert("Server issues");
                            }
                        break;
                        case "success":
                            var msg = JSON.parse(succ.msg);
                            var ser = JSON.parse(succ.user);
                            parse(msg, ser);
                        break;
                    }
                }
            })
        }

        setInterval(() => {
            getMes();
        }, 1000);
    </script>

</head>
<body onload="getMes()">
<div class="grid-container">
  <div class="item1"><?php echo $name?></div>
  <div class="item2">      
    <a href="back/chat_perexod.php?str=wel">Pick a chat</a>
    <a href="back/chat_perexod.php?str=pro">Profile</a>
    <a href="back/chat_perexod.php?str=log">Logout</a>
  </div>
  <div class="item3">
      <div id="chat" style="overflow: auto;"></div>
      <form onSubmit="sendMes(); return false;" autocomplete="off">
        <div class="col-auto">
            
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">Msg</div>
            </div>
                <input type="text" class="form-control" id="msg" placeholder="Message">
                <button type="button" class="btn btn-warning" onclick="sendMes()">Send</button>
            </div>
                
        </div>
      </form>
  </div>  
  <div class="item4">
      <div id="user">
      </div>
      <form autocomplete="off">
        <div style="margin-top:10px">
            <label for="id" class="col-sm-10 col-form-label" style="text-align:left">Share id with friends</label>
            <div class="col-sm-10">
                <input type="text" class="form-control-sm" id="id" placeholder="Room's id" value="<?php echo $room_id ?>">
            </div>
    </form>
  </div>
</div>
</body>
</html>