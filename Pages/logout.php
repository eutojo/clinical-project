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
    echo $_SESSION['logged_in'];
    //unset($_SESSION['logged_in']);
    $_SESSION['page'] = 'logout';
    require_once('../Components/menu-bar.php');
    ?>

    Logout
</body>