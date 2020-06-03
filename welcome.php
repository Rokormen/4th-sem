<!DOCTYPE html>
<?php
/**
 * \file welcome.php
 * 
 * Файл играет роль лобби для сайта. Здесь пользователь может найти комнату, либо создать ее самостоятельно
 *
 */
    include_once "head/sql_header.php";

    if(isset($_COOKIE['token'])){
        $token = $_COOKIE["token"];
    }

    $query = "SELECT users.login FROM users INNER JOIN tokens ON users.id = tokens.id WHERE tokens.token = '$token'"; /**\brief тест*/

    if(!mysqli_query($conn, $query)){
        echo "alert('Server down. Try again later')";
    } else {
        $table = mysqli_fetch_assoc(mysqli_query($conn, $query));
    }

?>
<html>
<head>
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="style/style.css">
    <title>Lobby</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script>
        /**
        * \brief Функция создающая комнату
        * Функция отправляет имя в файл-обработчик crate.php, принимает id комнаты и вызывает функцию connect
        *
        * \param name Имя комнаты
        */
        function create(name){
            $.ajax({
                method: "POST",
                url: "back/crate.php",
                data: {
                    name: name
                },
                success: function(succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "error":
                            switch (succ.er) {
                                case "db":
                                    alert("Server is down. Try again later");
                                break;
                            }
                        break;
                        case "success":
                            connect(succ.room_id);
                        break;
                }}
            })
        }

/**
 \brief Функция получения списка комнат
 Функция отправляет запрос файлу обработчику get_table.php, принимает список в формате JSON и размещает ее.
*/
        function get_table() {
            $.ajax({
                method: "GET",
                url: "back/get_table.php",
                success: function(succ) {
                    succ = JSON.parse(succ);
                    switch (succ.type) {
                        case "error":
                            switch (succ.er) {
                                case "db":
                                    alert("Cannot connect to the database. Try again later");
                                break;
                            }
                        break;
                        case "success":
                            $("#table").html(succ.table); 
                        break;
                    }
                }
            })
        }
        get_table();
        setInterval(() => {
            get_table();
        }, 1000);

        /**
        *\fn connect(id)
        *\brief Функция подключения к комнате
        *Функция принимает id комнаты и помещает его в cookie, затем перенаправляет юзера на страницу perexod.php
        *
        *\param id ID комнаты
        */
        function connect(id){
            document.cookie = "room_id="+id;
            location.assign("back/perexod.php");
        }

    </script>
</head>
<body>
<div class="grid-container">
  <div class="item1">Welcome to the chat room app, <?php echo $table["login"] ?></div>
  <div class="item2">
    <a href="profile.php">Profile</a>
    <?php if(isset($_COOKIE['room_id'])){
        echo "<a href='back/perexod.php'>Join last chat</a>";
    } ?>
    <a href="back/logout.php">Logout</a>
  </div>
  <div class="item3">
    <div id="table"></div>
  </div>  
  <div class="item4">
    <form autocomplete="off">
        <div style="margin-top:10px">
            <label for="id" class="col-sm-10 col-form-label" style="text-align:left">Connect by id</label>
            <div class="col-sm-10">
                <input type="text" class="form-control-sm" id="id" placeholder="Room's id">
            </div>
            <div class="col-sm-10" style="margin-top:10px">
                <button type="button" class="btn btn-warning btn-sm" onclick="connect($('#id').val())">Connect</button>
            </div>
    </form>
    <form autocomplete="off">
        <div style="margin-top:10px">
            <label for="id" class="col-sm-10 col-form-label" style="text-align:left">Create a room</label>
            <div class="col-sm-10">
                <input type="text" class="form-control-sm" id="name" placeholder="Name">
            </div>
            <div class="col-sm-10" style="margin-top:10px">
                <button type="button" class="btn btn-warning btn-sm" onclick="create($('#name').val())">Create</button>
            </div>
    </form>
  </div>
</div>
    
</body>
</html>