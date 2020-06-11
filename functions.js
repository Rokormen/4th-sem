    //A file containig all js functions in order to document them
    
    
    //welcome.php
    //=============================================================
    /**
    * A function that sends AJAX query to create a chatroom and on success assigns a new location for the user
    * @param {string} name Name of the chatroom
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
    * A function that sends AJAX query to the PHP file that get a table with every chatroom opened at the moment and sends it back JSON style. On success the function shows all the rooms active. 
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
    * A function that connects assigns a cookie with room id to the user and assigns a new location.
    * @param {string} id Chatroom ID
    */
    function connect(id){
        document.cookie = "room_id="+id;
        location.assign("back/perexod.php");
    }

    //register.php
    //============================================================================
    /**
     * A function that gets all the information from the registration fields and if they are not null sends data via AJAX to the worker file which creates a new user. 
     * On success will redirect you to the front page. 
     * In other cases will tell user whats an issue
     */
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

    //profile.php
    //===========================================================================
    /**
     * A function that creates a button with redirect to the admin page if the user is an admin. 
     * Contains PHP code in it that puts users status in a function (in comment brakets)
     */
    function adminhtml() {
        if (/*<?php echo $status ?> == 1 || <?php echo $status ?> == 2*/sampletext) {
            $("#admin").html("<a href='admin.php' class='btn btn-warning btn-sm'>Admin page</a>");
        }
    }

    /**
     * A function that will send an AJAX query to the worker file to change password if password form was filled correctly.
     * On success will alert the user that the new password was set
     */
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

    /**
     * A function to change email. If form was filled correctly will send an AJAX query to the worker file to change email.
     * On success will inform a user about a change
     * On failure will tell issue to the user
     */
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

    //login.php
    //========================================================================
    /**
     * A function for signing in to the site. If form was filled correctly will send an AJAX query to the worker file.
     * On success will generate a set a token for the user and redirect it to the lobby
     * On failure will tell why it happend
     */
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

    //chatroom.php
    //==========================================================
    /**
     * A function that sends an AJAX query containing a message if it is not null to the worker file
     * Uses PHP to get room id and users token from user (In comment brakets)
     */
    function sendMes () {
        if ($("#msg").val() != ""){
        $.ajax({
            method: "POST",
            url: "back/msg.php",
            data: {
                room: /*<?php echo "\"$room_id\"" ?>*/sampletext,
                msg: $("#msg").val(),
                token: /*<?php echo "\"$token\"" ?>*/sampletext
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

    /**
     * A function that accepts arrays with all messages and all users in a chatroom and constructs an html code to show to the user.
     * After parsing puts html code directly into the page with jquery
     * @param {array} msg 
     * @param {array} ser 
     */
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

    /**
     * A function that sends an AJAX query to the worker file to get message table. 
     * PHP is used to get rooms id. (In comment brakets)
     * On success calls parse function
     */
    function getMes() {
        $.ajax({
            method: "POST",
            url: "back/get_msg.php",
            data: {
                room: /*<?php echo "\"$room_id\"" ?>*/sampletext
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

    //admin.php
    //=============================================================================
    /**
     * A function that sends an AJAX query to promote a user when their login is written in the form
     * On success will alert of a success
     * On failure will tell a reason for it
     */
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

    /**
     * A function which sends an AJAX query to ban a user whose name was filled in the form
     * On success will inform of it
     * On failure will say a reason
     */
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

    /**
     * A function that sends AJAX query to release user whose name was in the form from ban
     * On success will inform of it
     * On failure will say a reason
     */
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
