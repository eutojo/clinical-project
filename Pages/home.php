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
        $homepage_component = ''. 
        '<div class ="login-page container" >'.
        '<h1>Welcome '. $_SESSION['name']. '</h1>'.
        '</div>';
    
        echo $homepage_component;
    } else {
        if(isset($_SESSION['user']) && $_SESSION['user']=='INVALID' ){
            unset($_SESSION['user']);
            echo '<script type="text/javascript">'.
                'invalidLogin();'.
                '</script>';
        }
        $login_component = ''.
        '<div class ="login-page container" >'.
        '<h1>Please login.</h1>'.
            '<form class="login" method="POST" action="../PHP/login-logic.php">'.
                '<!-- Username -->'.
                'Username'.
                '<span>'.
                    '<!-- For error handling -->'.
                '</span>'.
                '<input type="text" id="login-username" name="login-username">'.
                '<!-- Password -->'.
                'Password'.
                '<span>'.
                    '<!-- For error handling -->'.
                '</span>'.
                '<input type="password" id="login-password" name="login-password">'.
                '<input type="submit" value="Login">'.
            '</form>'.
        '</div>';

        echo $login_component;
    }

    
    
    ?>
</body>