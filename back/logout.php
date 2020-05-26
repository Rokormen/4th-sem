<?php
    setcookie("token", "", time()-3600);

    header("Location: ../index.php");
    die;
?>