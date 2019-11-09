<?php

$page = $_SESSION['page'];

$menu_bar = "".

'<div class="menu-bar container">'.
    '<div class="menu-bar item">';

        if($page == "index") {
            $menu_bar .= '<a href="./index.php"> Home </a>';
        } else {
            $menu_bar .= '<a href="../index.php"> Home </a>';
        }

    $menu_bar .=
    '</div>';
    if(isset($_SESSION['logged_in'])) {
        $menu_bar .=
        '<div class="menu-bar item">'. 
        '<a href="./logout.php"> Logout </a>'.
        '</div>';  
    }
        
    
    $menu_bar .=
'</div>'; 

echo $menu_bar;

?>