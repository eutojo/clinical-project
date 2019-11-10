<?php

    session_start();

    // Login bypass
    $_SESSION['debug'] = false;

    $username = $_POST["login-username"];
    $password = $_POST["login-password"];

    if($_SESSION['debug'] === true){
        $_SESSION['loggedin'] = true;
    } else {
        $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
        $query = "SELECT count(*) FROM Researcher WHERE Researcher_ID = '". $username ."' AND Password = '". $password ."'";
        $res = odbc_exec($conn, $query);
        
        // Researcher with username and password found
        if(odbc_result($res,1) > 0){
            // Successful login
            $_SESSION['researcher_ID'] = $username;
            $_SESSION['loggedin'] = true;
            // Get first name
            $query = "SELECT FirstName FROM Researcher WHERE Researcher_ID = '". $username ."' AND Password = '". $password ."'";
            $res = odbc_exec($conn, $query);
            $_SESSION['name'] = odbc_result($res, 1);
            // Check if admin
            $query = "SELECT Admin FROM Researcher WHERE Researcher_ID = '". $username ."' AND Password = '". $password ."'";
            $res = odbc_exec($conn, $query);
            $_SESSION['admin'] = odbc_result($res, 1);
        
        // Invalid username or password
        } else {
            $_SESSION['user'] = 'INVALID';
        }

    }

    header('Location:../index.php');