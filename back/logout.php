<?php
/**
 * \file logout.php
 * 
 * Файл-обработчик для выхода с сайта. Уничтожает cookie связанные с личным токеном и токеном комнаты
 *
 */
    setcookie("token", null, time()-3600, '/');

    setcookie("room_id", null, time()-3600, '/');

    header("Location: ../index.php");
    die;
?>
