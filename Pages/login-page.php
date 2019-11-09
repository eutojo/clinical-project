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
    $_SESSION['logged_in'] = 'true';
    $_SESSION['page'] = 'login';
    require_once('../Components/menu-bar.php');

    if(isset($_SESSION['logged_in'])){
        echo $_SESSION['logged_in'];

    }
    ?>

    
    Logged in!
</body>