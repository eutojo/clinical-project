<?php

$menu_bar = "".

'<div class="menu-bar container">'.
    '<div class="menu-bar item">';

        if($_SESSION['page'] == "index") {
            $menu_bar .= '<a href="./index.php"> Home </a>';
        } else {
            $menu_bar .= '<a href="../index.php"> Home </a>';
        }

    $menu_bar .=
    '</div>';
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        if($_SESSION['page'] == "index") {
            $menu_bar .=
                '<div class="menu-bar item">'. 
                '<a href="./Pages/logout.php"> Logout </a>'.
                '</div>';  
        } else {
            $menu_bar .=
                '<div class="menu-bar item">'. 
                '<a href="./logout.php"> Logout </a>'.
                '</div>';  
        }    
    }
        
    
    $menu_bar .=
'</div>'; 

echo $menu_bar;

?>