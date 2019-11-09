<?php

    $login_component = ''.
    '<div class ="login-page container" >'.
        '<h1>Please login.</h1>'.
        '<form method="POST" action="./PHP/login-logic.php">'.
            '<!-- Username -->'.
            'Username'.
            '<span id="login-username">'.
                '<!-- For error handling -->'.
            '</span>'.
            '<input type="text">'.
            '<!-- Password -->'.
            'Password'.
            '<span id="login-password">'.
                '<!-- For error handling -->'.
            '</span>'.
            '<input type="password">'.
            '<input type="submit" value="Login">'.
        '</form>'.
    '</div>';

    echo $login_component;
?>