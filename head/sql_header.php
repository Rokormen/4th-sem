<?php

$dbServername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "lab6";

    $conn = mysqli_connect( $dbServername , $dbUsername , $dbPassword , $dbName );

    if(!$conn){
        die('Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }


?>