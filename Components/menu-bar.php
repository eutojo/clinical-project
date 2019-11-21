<?php

$menu_bar = "".

'<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>'.

'<div class="menu-bar container">'.
    // Home button
    '<div class="menu-bar item">'.
        '<a href="./home.php"> Home </a>'.
    '</div>';
    
    // User is logged in
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

        // If user is admin, show the assignments
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
            $menu_bar .=
                // Access to reserachers
                '<div class="menu-bar item">'. 
                '<a href="./researchers.php"> Researchers </a>'.
                '</div>'.

                // Access to asssignments
                '<div class="menu-bar item">'. 
                '<a href="./assignments.php"> Assignments </a>'.
                '</div>'; 
        }

        $menu_bar .=
            // Subjects
            '<div class="menu-bar item">'. 
            '<a href="./subjects.php"> Subjects </a>'.
            '</div>';

            if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
                $menu_bar .=
                '<div class="menu-bar item">'. 
                '<a href="./summary.php"> Data Summary </a>'.
                '</div>';
            }
            $menu_bar .=
            // Logout button
            '<div class="menu-bar item">'. 
            '<a href="../PHP/logout-logic.php">Logout </a>'.
            '</div>';    
    }
        
    
    $menu_bar .=
'</div>'; 

echo $menu_bar;

?>