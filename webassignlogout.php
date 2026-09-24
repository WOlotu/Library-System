<?php
    session_start();
    //destroys session id
    include 'header2.php';
    session_destroy();
    header("Location: login.php");
?>