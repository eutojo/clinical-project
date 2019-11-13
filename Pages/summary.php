<?php
session_start(); 
$_SESSION['page'] = 'researchers';
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
include('../TeeChart/sources/TChart.php');
require_once('../Components/menu-bar.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    if($_SESSION['admin'] == 1) {
        
    } else {
        echo '<script type="text/javascript">'.
            'adminPrompt();'.
            '</script>';
    }
    
} else {
    echo '<script type="text/javascript">'.
            'loginPrompt();'.
            '</script>';
}
