<?php

    session_start();

    // Login bypass
    $_SESSION['debug'] = true;

    if($_SESSION['debug'] === true){
        $_SESSION['loggedin'] = true;
    }

    header('Location:../index.php');