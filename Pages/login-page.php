<?php
    session_start(); 
?>
<head>
    <!-- Link to stylesheet -->
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/menu-bar.css">
    <link rel="stylesheet" href="../CSS/login.css">

    <!-- Document title/ -->
    <title>M2C2 2019</title>
</head>
<body>
    <?php
    $_SESSION['loggedin'] = true;
    $_SESSION['page'] = 'login';
    require_once('../Components/menu-bar.php');

    if(isset($_SESSION['loggedin'])){
        echo $_SESSION['loggedin'];

    }
    ?>

    
    Logged in!
</body>