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
    if (session_id() === "") { 
        session_start(); 
        echo "New session";
    } else {
        echo "resume sesion";
    }

    echo session_id();

    $_SESSION['page'] = 'index';
    require_once('./Components/menu-bar.php');

    if(isset($_SESSION['logged_in'])){
        echo $_SESSION['logged_in'];

    } else {
        require_once('./Components/login.php');
    }
    
    ?>
</body>