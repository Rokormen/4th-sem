<?php
    setcookie("token", null, time()-3600, '/');
    setcookie("chat_id", null, time()-3600, '/');

    header("Location: ../index.php");
    die;
?>
