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

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        session_unset(); 
        
    }

    header('Location: ../index.php');
    
    ?>

</body>