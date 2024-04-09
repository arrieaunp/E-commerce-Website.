<?php
    session_start();
    session_destroy();
    setcookie("token", $jwt, time() - 3600, "/", "", true, true);
    header("Location: index.php");
?>