<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");

    setcookie('pass', '', 0);
?>