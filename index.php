<?php
    session_start(); 
?>

<head>
    <!-- Link to stylesheet -->
    <link rel="stylesheet" href="./CSS/styles.css">
    <link rel="stylesheet" href="./CSS/menu-bar.css">
    <link rel="stylesheet" href="./CSS/login.css">

    <!-- Document title/ -->
    <title>M2C2 2019</title>
</head>
<body>
    <?php
   
    require_once('./Components/menu-bar.php');

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        echo $_SESSION['loggedin'];
    } else {
        
        require_once('./Components/login-component.php');
    }

    $_SESSION['page'] = 'index';
    
    ?>
</body>