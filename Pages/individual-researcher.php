<?php
    session_start(); 
    $_SESSION['page'] = 'individual-researcher';
?>

<head>
    <!-- Link to stylesheet -->
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/menu-bar.css">

    <script src="../JS/functions.js">
    </script>

    <!-- Document title/ -->
    <title>M2C2 2019</title>
</head>
<body>
    <?php
   
    require_once('../Components/menu-bar.php');

    ?>

    <?php
    // User has logged in
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        // Check if page is correct
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);

            // Check if Subject exists
            $query = "SELECT FirstName, Surname, Admin FROM Researcher WHERE Researcher_ID = '". $id ."'";
            $res = odbc_exec($conn, $query);

            $count = 0;
            while(odbc_fetch_row($res)){
                if($count > 0){
                    break;
                }
                $count++;
            }

            // Subject exists
            if($count > 0) {
                // Obtain subject's details
                $firstname = odbc_result($res, 1);
                $surname = odbc_result($res, 2);
                $admin = odbc_result($res, 3);

                require_once('../Components/subject-table.php');

                require_once('../Components/new-assignment.php');
            
            // Subject doesn't exist
            } else {
                // Direct to researcher page if admin
                if($_SESSION['admin'] == 1){
                    echo '<script type="text/javascript">'.
                    'researcherPrompt(1);'.
                    '</script>';
                // Direct to login if not admin
                } else {
                    echo '<script type="text/javascript">'.
                    'researcherPrompt(0);'.
                    '</script>';
                }
            }
    
        // Page invalid
        } else {
             // Direct to researcher page if admin
             if($_SESSION['admin'] == 1){
                echo '<script type="text/javascript">'.
                'researcherPrompt(1);'.
                '</script>';
            // Direct to login if not admin
            } else {
                echo '<script type="text/javascript">'.
                'researcherPrompt(0);'.
                '</script>';
            }
        }

    // Not logged in, redirect
    } else {
        echo '<script type="text/javascript">'.
                'loginPrompt();'.
                '</script>';
    }

    
    
    ?>
</body>