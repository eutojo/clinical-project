<?php
    if(session_id() == ''){
        session_start();
    }
  
    $_SESSION['page'] = 'home';
?>

<head>
    <!-- Link to stylesheet -->
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/menu-bar.css">
    <link rel="stylesheet" href="../CSS/login.css">

    <script src="../JS/functions.js">
    </script>

    <!-- Document title/ -->
    <title>M2C2 2019</title>
</head>
<body>
    <?php
   
    require_once('../Components/menu-bar.php');

    // User has logged in
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
       require_once('../Components/home-component.php');
    } else {
        if(isset($_SESSION['user']) && $_SESSION['user']=='INVALID' ){
            unset($_SESSION['user']);
            echo '<script type="text/javascript">'.
                'invalidLogin();'.
                '</script>';
        }
        require_once('../Components/login-component.php');
    }

    
    
    ?>
</body>